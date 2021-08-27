<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$EMAIL_FOLDER = 'emails/';
$EMAIL_FROM_NAME = 'ITNT-2020';
$EMAIL_IMAPPATH = '{imap.yandex.ru:993/imap/ssl/novalidate-cert}registration';


//Send E-mail letter using PHPMailer
function sendLetter_TH($msg, $isTech)
{
	//TODO - Disabled
	throw new Exception('Forbidden.');
	exit();

	$mail = new PHPMailer;

	if ($isTech) {
		$from = MAIL_TECH_ADDRESS;
		$username = MAIL_TECH_ADDRESS;
		$password = MAIL_TECH_PASSWORD;
	} else {
		$from = MAIL_SEC_ADDRESS;
		$username = MAIL_SEC_ADDRESS;
		$password = MAIL_SEC_PASSWORD;
	}

	//Server settings
	$mail->SMTPDebug = 0; // 0 = off (for production use) 1 = client messages 2 = client and server messages
	$mail->isSMTP(); // Set mailer to use SMTP
	$mail->Host = 'smtp.yandex.ru'; // Specify main and backup SMTP servers
	$mail->Username = $username; // SMTP username
	$mail->Password = $password; // SMTP password
	$mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465; // TCP port to connect to
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth = true; // Enable SMTP authentication

	// Recepients
	global $EMAIL_FROM_NAME;
	$mail->setFrom($from, $EMAIL_FROM_NAME);
	// TO
	foreach ($msg['TO'] as $row) {
		$mail->addAddress($row['Email'], $row['Name']); // Add a recipient
	}
	// CC
	if (isset($msg['CC'])) {
		foreach ($msg['CC'] as $row) {
			$mail->addCC($row['Email'], $row['Name']);
		}
	}
	// BCC
	if (isset($msg['BCC'])) {
		foreach ($msg['BCC'] as $row) {
			$mail->addBCC($row['Email'], $row['Name']);
		}
	}

	// Attachments
	if (isset($msg['Attachments'])) {
		foreach ($msg['Attachments'] as $file) {
			// Check file existence
			if (!files_is_exists($file['path'])) throw new DataException('Файл-вложение отсутствует', array('function' => __FUNCTION__, 'value' => array($msg, $file['path'])));

			$mail->addAttachment(files_get_absolute_path($file['path']), $file['name']);
		}
	}

	// Subject
	$mail->Subject = $msg['Subject'];

	//Content
	$mail->isHTML(true);
	$mail->Body = $msg['Text'];
	//$mail->AltBody = $msg['PlainText'];

	if (!$mail->send()) {
		// Remove text from message before saving to log
		unset($msg['Text']);
		throw new DataException('Письмо не может быть отправлено', array('function' => __FUNCTION__, 'value' => array($msg, $mail->ErrorInfo)));
	} else {
		//Save letter to IMAP folder
		global $EMAIL_IMAPPATH;
		if (isset($EMAIL_IMAPPATH)) {
			$imapStream = @imap_open($EMAIL_IMAPPATH, $mail->Username, $mail->Password);
			@imap_append($imapStream, $EMAIL_IMAPPATH, $mail->getSentMIMEMessage());
			@imap_close($imapStream);
		}
	}
}

// Add E-mail letter
function email_add_TH($msg)
{
	// Create meta-info
	$meta = array(
		'TO' => $msg['TO'], //array(['Name', 'Email'])
		'CC' => $msg['CC'],
		'BCC' => $msg['BCC'],
		'Subject' => $msg['Subject'],
		'Attachments' => $msg['Attachments'],
		'Extra' => $msg['Extra']
	);

	// Create email
	$email = array(
		'MetaInfo' => json_encode($meta, JSON_UNESCAPED_UNICODE),
		'Status' => 'P'
	);
	if (wp_get_current_user()->ID !== 0) $email['ID_User'] = wp_get_current_user()->ID;

	// Decode postpone
	$pp = isset($msg['Postpone']) ? $msg['Postpone'] : '15m';
	if ('ad' !== $pp) {
		$pp_n = mb_substr($pp, 0, mb_strlen($pp) - 1);
		$pp_u = mb_substr($pp, mb_strlen($pp) - 1);
		if ('s' === $pp_u) $pp_u = 'seconds';
		else if ('m' === $pp_u) $pp_u = 'minutes';
		else if ('h' === $pp_u) $pp_u = 'hours';
		else if ('d' === $pp_u) $pp_u = 'days';
		$senddt = date('Y-m-d H:i:s', strtotime('+' . $pp_n . ' ' . $pp_u)); //+5 minutes
		$email['SendDateTime'] = $senddt;
	}

	try {
		global $wpdb;
		$wpdb->query('START TRANSACTION');

		global $EMAIL_FOLDER;
		// Create new message
		if (is_null($msg['ID'])) {
			$ID = db_add_TH('zi_ab_outmails', $email);
		}
		// Edit existing message
		else {
			$email['ID'] = (int)$msg['ID'];
			db_set_TH('zi_ab_outmails', $email);
		}

		// Save text file
		$subfolder = $EMAIL_FOLDER . $ID . '/';
		files_make_directory_TH($subfolder);
		files_write_text_TH($msg['Text'], $subfolder . 'msg.txt');

		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		$wpdb->query('ROLLBACK');
		throw $e;
	}
}

// Recheck email table and send all ready E-mail letters
function email_recheck()
{
	global $wpdb;

	$results = $wpdb->get_results("
		SELECT t.*
		FROM zi_ab_outmails t 
		WHERE t.Status<>'F' AND t.SendDateTime < NOW()");


	foreach ($results as $row) {
		processLetter($row);
	}
}

// Process single letter
function processLetter($outmail)
{
	global $EMAIL_FOLDER;
	try {
		// Decode message
		$msg = json_decode($outmail->MetaInfo, true);
		$msg['Text'] = files_read_text_TH($EMAIL_FOLDER . $outmail->ID . '/msg.txt');

		// Send message
		$isTech = $msg['Extra'] && $msg['Extra']['Tech'] === 'Y';
		sendLetter_TH($msg, $isTech);

		// Change status
		$upd = array(
			'ID' => $outmail->ID,
			'Status' => 'F',
			'SendDateTime' => date('Y-m-d H:i:s')
		);
		db_set_TH('zi_ab_outmails', $upd);
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
		try {
			db_set_field_TH('zi_ab_outmails', 'Status', 'X', $outmail->ID);
		} catch (Exception $e) {
		}
		return false;
	}
	return true;
}

/**
 * Get email
 */
function email_get_TH($ID)
{
	global $EMAIL_FOLDER;
	$row = db_get('zi_ab_outmails', $ID);
	$row->Text = files_read_text_TH($EMAIL_FOLDER . $ID . '/msg.txt');

	return $row;
}

/**
 * Get email on AJAX request
 */
add_action('wp_ajax_email_get_json', 'email_get_json');
function email_get_json()
{
	$ID = (int)$_GET['ID'];

	try {
		$msg = email_get_TH($ID);
		echo json_encode(array(1, $msg));
	} catch (DataException $e) {
		echo json_encode(array(2, 'Ошибка чтения текста письма'));
	}
	exit();
}

// Get number of active emails
add_action('wp_ajax_email_get_count_json', 'email_get_count_json');
function email_get_count_json()
{
	global $wpdb;

	$row = $wpdb->get_row("
		SELECT 
			SUM(t.Status='X') as ErrorCount,
			SUM(t.Status='P') as AwaitCount
		FROM zi_ab_outmails t
		WHERE t.Status <> 'F'", "ARRAY_N");

	echo json_encode($row);
	exit();
}

// List all emails
add_action('wp_ajax_email_list_json', 'email_list_json');
function email_list_json()
{
	global $wpdb;

	$results = $wpdb->get_results("
		SELECT t.*, wu.display_name
		FROM zi_ab_outmails t 
			LEFT JOIN zi_users wu ON wu.ID=t.ID_User");

	echo json_encode($results);
	exit();
}

// List all emails (server-side processing)
add_action('wp_ajax_email_list_s_json', 'email_list_s_json');
function email_list_s_json()
{
	global $wpdb;

	$rowscount = $wpdb->get_var(
		"SELECT COUNT(*)
		FROM zi_ab_outmails"
	);

	$order_field = $_GET['columns'][$_GET['order'][0]['column']]['data'];
	$order_field = $order_field === 'Status' ? 'Status' : 'SendDateTime';

	$order_dir = 'ASC';
	if ($_GET['order'][0]['dir'] === 'desc') $order_dir = 'DESC';

	$G_search = $_GET['search']['value'];
	$G_searchI = g_ckl($G_search);

	// Show all data
	if ('-1' === $_GET['length']) {
		$_GET['start'] = 0;
		$_GET['length'] = 65535;
	}

	global $wpdb;
	$result = $wpdb->get_results($wpdb->prepare(
		"SELECT t.*, wu.display_name
		FROM zi_ab_outmails t 
			LEFT JOIN zi_users wu ON wu.ID=t.ID_User
		WHERE t.MetaInfo LIKE %s OR t.MetaInfo LIKE %s
		ORDER BY t.$order_field $order_dir, t.SendDateTime DESC
		LIMIT %d, %d",
		'%' . $G_search . '%',
		'%' . $G_searchI . '%',
		$_GET['start'],
		$_GET['length']
	));

	$json_data = array(
		"draw"            => intval($_REQUEST['draw']),
		"recordsTotal"    => intval($rowscount),
		"recordsFiltered" => intval($rowscount),
		"data"            => $result,
	);
	echo json_encode($json_data);

	exit();
}

/**
 * Force sendimg email
 */
add_action('wp_ajax_email_force_json', 'email_force_json');
function email_force_json()
{
	$id = (int) $_POST['ID'];

	global $wpdb;
	try {
		$wpdb->query('START TRANSACTION');
		$row = db_get('zi_ab_outmails', $id);
		processLetter($row);

		echo json_encode(array(1, 'Письмо отправлено'));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		$wpdb->query('ROLLBACK');
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, 'Ошибка отправления письма'));
	}

	exit();
}

/**
 * Retry sendimg email (remove Error-flag)
 */
add_action('wp_ajax_email_retry_json', 'email_retry_json');
function email_retry_json()
{
	$id = (int) $_POST['ID'];

	global $wpdb;
	try {
		$wpdb->query('START TRANSACTION');
		$email = array(
			'ID' => $id,
			'SendDateTime' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
			'Status' => 'P'
		);
		db_set_TH('zi_ab_outmails', $email);

		echo json_encode(array(1, 'Письмо возвращено'));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		$wpdb->query('ROLLBACK');
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, 'Ошибка возвращения письма'));
	}

	exit();
}


// Remove email
add_action('wp_ajax_email_remove_json', 'email_remove_json');
function email_remove_json()
{
	$id = (int) $_POST['ID'];

	global $wpdb;
	try {
		$wpdb->query('START TRANSACTION');
		db_remove_TH('zi_ab_outmails', $id);
		echo json_encode(array(1, 'Письмо удалено'));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		$wpdb->query('ROLLBACK');
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, 'Ошибка удаления письма'));
	}

	exit();
}


// Recheck all emails
add_action('wp_ajax_email_recheck_json', 'email_recheck_json');
function email_recheck_json()
{
	email_recheck();
	exit();
}
