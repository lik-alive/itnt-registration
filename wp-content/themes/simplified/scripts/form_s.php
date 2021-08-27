<?php
$CONFID = 2;

/**
 * Get registration info by link
 */
function form_get_reg_by_link($link, $strict = false)
{
	global $wpdb, $CONFID;

	// Search only among current conference's participants
	if ($strict) $cond = "AND p.ID_Conf=$CONFID";

	$result = $wpdb->get_row($wpdb->prepare(
		"SELECT * 
		FROM zi_ab_participants p
		WHERE p.Link = %s $cond",
		$link
	));

	return $result;
}

/**
 * Get articles for registration
 */
function form_get_reg_articles($id)
{
	global $wpdb;
	$results = $wpdb->get_results(
		"SELECT a.*, al.Format
		FROM zi_ab_articles a INNER JOIN zi_ab_arlinks al ON al.ID_Article=a.ID
		WHERE al.ID_Participant=$id"
	);
	return $results;
}

/**
 * Get events for registration
 */
function form_get_reg_events($id)
{
	global $wpdb;
	$results = $wpdb->get_results(
		"SELECT *
		FROM zi_ab_events e INNER JOIN zi_ab_evlinks el ON el.ID_Event=e.ID
		WHERE el.ID_Participant=$id"
	);

	$events = array();
	foreach ($results as $row) {
		$events[$row->ID_Event] = 'Y';
	}
	return $events;
}

/**
 * Get workshops for registration
 */
function form_get_reg_workshops($id)
{
	global $wpdb;
	$results = $wpdb->get_results(
		"SELECT *
		FROM zi_ab_workshops w INNER JOIN zi_ab_wslinks wl ON wl.ID_Workshop=w.ID
		WHERE wl.ID_Participant=$id"
	);

	$workshops = array();
	foreach ($results as $row) {
		$workshops[$row->ID_Workshop] = 'Y';
	}
	return $workshops;
}

/**
 * Search for papers by ID or info
 */
add_action('wp_ajax_search_papers_json', 'search_papers_json');
add_action('wp_ajax_nopriv_search_papers_json', 'search_papers_json');
function search_papers_json()
{
	$kw = $_GET['kw'];
	$kwI = g_ckl($kw);

	global $wpdb, $CONFID;
	$result = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * 
		FROM zi_ab_articles a
		WHERE a.ID_Conf=$CONFID AND (
			a.ECID LIKE %s
			OR a.Title LIKE %s  OR a.Title LIKE %s
			OR a.Authors LIKE %s  OR a.Authors LIKE %s)",
			$kw . '%',
			'%' . $kw . '%',
			'%' . $kwI . '%',
			'%' . $kw . '%',
			'%' . $kwI . '%'
		)
	);

	echo json_encode($result);
	exit();
}

/**
 * Create random string
 */
function generateRandomString($length)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

/**
 * Generate random link
 */
function generateRandomLink()
{
	for ($i = 0; $i < 3; $i++) {
		$link = generateRandomString(8);
		$result = form_get_reg_by_link($link);

		if (is_null($result)) return $link;
	}
	//Triple attempt has failed
	return null;
}

/**
 * Get participant info by email
 */
function getParticipantByEMail($email)
{
	global $wpdb, $CONFID;
	$result = $wpdb->get_row($wpdb->prepare(
		"SELECT * 
		FROM zi_ab_participants p
		WHERE p.EMail = %s AND p.ID_Conf = $CONFID",
		$email
	));

	return $result;
}

/**
 * Get participant linked with the specified article ID
 */
function getParticipantByArticle($ID_Article)
{
	global  $wpdb;
	$result = $wpdb->get_row(
		"SELECT *
		FROM zi_ab_participants p INNER JOIN zi_ab_arlinks al ON al.ID_Participant=p.ID
		WHERE al.ID_Article = $ID_Article"
	);
	return $result;
}

/**
 * Check if the email exists in DB
 */
function emailExists($email)
{
	$part = getParticipantByEMail($email);
	return isset($part);
}

/**
 * Mask the E-mail in form of asd***@gmail.com
 */
function maskEMail($email)
{
	$arr = explode('@', $email);
	$visibleCount = (mb_strlen($arr[0]) < 5) ? 1 : 3;

	return mb_substr($arr[0], 0, $visibleCount) . str_repeat('*', mb_strlen($arr[0]) - $visibleCount) . '@' . $arr[1];
}

/**
 * Create or update the registration
 */
add_action('wp_ajax_form_update_json', 'form_update_json');
add_action('wp_ajax_nopriv_form_update_json', 'form_update_json');
function form_update_json()
{
	//Define domain
	if ($_POST['Lang'] === 'R') {
		load_textdomain('translate', get_template_directory() . '/languages/ru_RU.mo');
	}

	global $wpdb, $CONFID;
	$wpdb->query('START TRANSACTION');

	$reg = g_crd(array(
		'Link' => $_POST['Link'],
		// Personal info
		'Honorific' => $_POST['Honorific'],
		'FirstName' => $_POST['fname'],
		'LastName' => $_POST['lname'],
		'EMail' => $_POST['email'],

		// Scientific degree
		'SciStatus' => $_POST['SciStatus'],

		// Residency
		'Country' => $_POST['Country'],

		// Organization
		'Organization' => $_POST['Organization'],
		'OrganizationCity' => $_POST['OrganizationCity'],
		'PaymentFromOrg' => isset($_POST['PaymentFromOrg']) ? 'Y' : 'N',

		// Participation info
		'YouthSchool' => isset($_POST['YouthSchool']) ? 'Y' : 'N',

		'DateTime' => date('Y-m-d H:i:s')
	));

	$articles = array();
	for ($i = 1; $i <= 2; $i++) {
		if (g_ies($_POST["ID_Article$i"])) continue;

		$ID_Article = (int) $_POST["ID_Article$i"];
		$article = db_get('zi_ab_articles', $ID_Article);
		$article->Format = $_POST["Format$i"];

		$articles[$ID_Article] = $article;
	}

	$events = array(
		'2' => $_POST['Event2'],
		'3' => $_POST['Event3'],
		'4' => $_POST['Event4'],
		'5' => $_POST['Event5'],
		'6' => $_POST['Event6'],
		'7' => $_POST['Event7'],
		'8' => $_POST['Event8']
	);

	$workshops = g_crd(array(
		'1' => $_POST['Track1'],
		'2' => $_POST['Track2'],
		'3' => $_POST['Track3']
	));

	try {
		// Confirmation letter
		$confletter = false;
		$eventletter = false;
		$uploadletter = false;
		$created = false;

		// Create 
		if (empty($_POST['Link'])) {
			$created = true;
			$confletter = true;
			$uploadletter = true;
			//Check if the email already exists
			if (emailExists($reg['EMail']))
				throw new Exception(_gl("This E-mail has already been registered. If you want to edit registration data, please, use your registration edit link"));

			//Check if the papers have already been linked
			foreach ($articles as $id => $article) {
				$par = getParticipantByArticle($id);
				if (isset($par)) {
					$msg = _gl("The paper #%ECID% has already been selected by another participant (%EMAIL%). Please, contact Editorial Board or the participant.");
					$msg = str_replace("%ECID%", $article->ECID, $msg);
					$msg = str_replace("%EMAIL%", maskEMail($par->EMail), $msg);
					throw new Exception($msg);
				}
			}

			//Set link for editing
			$link = generateRandomLink();
			if (is_null($link))
				throw new DataException('Ошибка создания уникального link', array('function' => __FUNCTION__));

			//Save registration to database
			$reg['Link'] = $link;
			$reg['ID_Conf'] = $CONFID;
			$ID_Participant = db_add_TH('zi_ab_participants', $reg);

			// Save articles to database
			foreach ($articles as $id => $article) {
				db_add_TH('zi_ab_arlinks', array('ID_Article' => $id, 'ID_Participant' => $ID_Participant, 'Format' => $article->Format));
			}

			// Save events to database
			foreach ($events as $id => $status) {
				if ($status === 'Y') db_add_TH('zi_ab_evlinks', array('ID_Event' => $id, 'ID_Participant' => $ID_Participant));
			}

			// Save workshops to database
			foreach ($workshops as $id => $status) {
				if ($status === 'Y') db_add_TH('zi_ab_wslinks', array('ID_Workshop' => $id, 'ID_Participant' => $ID_Participant));
			}
		}
		//Edit
		else {
			//Find ID by link
			$link = $reg['Link'];
			$strict = wp_get_current_user()->user_login !== 'itntadm';
			$curreg = form_get_reg_by_link($link, $strict);
			if (is_null($curreg))
				throw new DataException('Неверный link на регистрацию', array('function' => __FUNCTION__, 'value' => $link));

			$ID_Participant = $curreg->ID;

			//Check if the email is changed and new one already exists
			if ($reg['EMail'] !== $curreg->EMail && emailExists($reg['EMail']))
				throw new Exception(_gl("This E-mail has already been registered. If you want to edit registration data, please, use your registration edit link"));

			//Check if the papers have already been linked
			foreach ($articles as $id => $article) {
				$par = getParticipantByArticle($id);
				if (isset($par) && $par->EMail !== $reg['EMail'] && $par->EMail !== $curreg->EMail) {
					$msg = _gl("The paper #%ECID% has already been selected by another participant (%EMAIL%). Please, contact Editorial Board or the participant.");
					$msg = str_replace("%ECID%", $article->ECID, $msg);
					$msg = str_replace("%EMAIL%", maskEMail($par->EMail), $msg);
					throw new Exception($msg);
				}
			}

			//Save to database
			$reg['ID'] = $ID_Participant;
			db_set_TH('zi_ab_participants', $reg);

			// Check if major parts were changed
			if (
				$reg['FirstName'] !== $curreg->FirstName ||
				$reg['LastName'] !== $curreg->LastName ||
				$reg['EMail'] !== $curreg->EMail ||
				$reg['SciStatus'] !== $curreg->SciStatus
			) {
				$confletter = true;
			}

			// Save articles to database
			$curarticles = db_list_cond('zi_ab_arlinks', "ID_Participant=$ID_Participant");
			// Process removed
			foreach ($curarticles as $curarticle) {
				$article = $articles[$curarticle->ID_Article];
				if (is_null($article)) {
					db_remove_TH('zi_ab_arlinks', $curarticle->ID);
					$confletter = true;
				} else {
					// Check if the format was changed
					if ($curarticle->Format !== $article->Format) {
						db_set_TH('zi_ab_arlinks', array('ID' => $curarticle->ID, 'Format' => $article->Format));
					}
					unset($articles[$curarticle->ID_Article]);
				}
			}
			// Add new
			foreach ($articles as $id => $article) {
				db_add_TH('zi_ab_arlinks', array('ID_Article' => $id, 'ID_Participant' => $ID_Participant, 'Format' => $article->Format));
				$confletter = true;
			}

			// Save events to database
			$curevents = db_list_cond('zi_ab_evlinks', "ID_Participant=$ID_Participant");
			// Process removed
			foreach ($curevents as $ev) {
				if ($events[$ev->ID_Event] !== 'Y') {
					db_remove_TH('zi_ab_evlinks', $ev->ID);
					$eventletter = true;
				} else unset($events[$ev->ID_Event]);
			}
			// Add new
			foreach ($events as $id => $status) {
				if ($status === 'Y') {
					db_add_TH('zi_ab_evlinks', array('ID_Event' => $id, 'ID_Participant' => $ID_Participant));
					$eventletter = true;
				}
			}

			// Save workshops to database
			$curworkshops = db_list_cond('zi_ab_wslinks', "ID_Participant=$ID_Participant");
			// Process removed
			foreach ($curworkshops as $ws) {
				if ($workshops[$ws->ID_Workshop] !== 'Y') {
					db_remove_TH('zi_ab_wslinks', $ws->ID);
				} else unset($workshops[$ws->ID_Workshop]);
			}
			// Add new
			foreach ($workshops as $id => $status) {
				if ($status === 'Y') {
					db_add_TH('zi_ab_wslinks', array('ID_Workshop' => $id, 'ID_Participant' => $ID_Participant));
				}
			}
		}

		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		$wpdb->query('ROLLBACK');
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, _gl("Registration failed. Please, contact the Editorial Board")));
		exit();
	} catch (Exception $e) {
		$wpdb->query('ROLLBACK');
		echo json_encode(array(2, $e->getMessage()));
		exit();
	}

	// Send letters
	try {
		//Do not send event letter for foreigners
		$cis = array("ARM", "AZE", "BLR", "KAZ", "KGZ", "MDA", "RUS", "TJK", "TKM", "UKR", "UZB");
		if (!in_array($reg['Country'], $cis, true)) {
			$eventletter = false;
		}

		//Send confirmation letter
		if ($confletter) {
			// Remove all previous unsent letters
			global $wpdb;
			$wpdb->query(
				"DELETE FROM zi_ab_outmails
				WHERE MetaInfo LIKE '%\"Link\":\"$link\"%' AND MetaInfo LIKE '%\"Type\":\"RegConf\"%' AND Status='P'"
			);

			letters_sendRegConfirmation_TH($link, 300);
		}

		// Send upload letter (only for new registrations)
		if ($uploadletter) {
			letters_sendUploader_TH($link, 500);
		}

		/*
		//Send event letter
		if ($eventletter) letters_sendEvents_TH($link, 300);
		*/
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
	}

	echo json_encode(array(1, '', $link, $created, $confletter));
	exit();
}

/**
 * Send restore link
 */
add_action('wp_ajax_form_restore_json', 'form_restore_json');
add_action('wp_ajax_nopriv_form_restore_json', 'form_restore_json');
function form_restore_json()
{
	//Define domain
	if ($_POST['Lang'] === 'R') {
		load_textdomain('translate', get_template_directory() . '/languages/ru_RU.mo');
	}

	try {
		$part = getParticipantByEMail($_POST['email']);
		//Check if the E-mail exists	
		if (!isset($part)) throw new Exception(_gl('Registration with the specified E-mail does not exists'));

		// Check if the last restoration was earlier than 5 minutes ago
		global $wpdb;
		$last = $wpdb->get_var(
			"SELECT o.SendDateTime
			FROM zi_ab_outmails o
			WHERE o.MetaInfo LIKE '%\"Link\":\"{$part->Link}\"%' AND o.MetaInfo LIKE '%\"Type\":\"Restore\"%'
			ORDER BY o.SendDateTime DESC"
		);

		if (isset($last)) {
			$minutes = ((new DateTime())->getTimestamp() - (new DateTime($last))->getTimestamp()) / 60;
			if ($minutes < 5) throw new Exception(_gl('Please, wait 5 minutes for the second request'));
		}

		letters_sendRestore_TH($part->Link, 5);

		echo json_encode(array(1));
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, _gl("Recovery failed. Please, contact the Editorial Board")));
		exit();
	} catch (Exception $e) {
		echo json_encode(array(2, $e->getMessage()));
		exit();
	}
	exit();
}

/**
 * Search for the registration
 */
add_action('wp_ajax_search_json', 'search_json');
function search_json() {
	$kw = $_GET['Data'];
	global $wpdb;
	$results = $wpdb->get_results($wpdb->prepare(
		"SELECT p.Link
		FROM zi_v_report r JOIN zi_ab_participants p ON p.ID=r.ID
		WHERE r.FirstName LIKE %s
			OR r.LastName LIKE %s
			OR r.EMail LIKE %s
			OR r.ECID=%d",
		'%' . $kw . '%',
		'%' . $kw . '%',
		'%' . $kw . '%',
		$kw
	));
	echo json_encode($results);
	exit();
}

/**
 * Search for the article by ECID
 */
add_action('wp_ajax_ecid_search_json', 'ecid_search_json');
function ecid_search_json() {
	$ecid = (int) $_GET['ECID'];
	global $wpdb, $CONFID;
	$row = $wpdb->get_row(
		"SELECT a.Authors, a.Title, CONCAT(r.FirstName, ' ', r.LastName) as Name, r.EMail
		FROM zi_ab_articles a 
			LEFT JOIN zi_v_report r ON r.ECID=a.ECID
		WHERE a.ECID=$ecid AND a.ID_Conf=$CONFID"
	);
	echo json_encode($row);
	exit();
}