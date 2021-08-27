<?php

//Get letter
add_action('wp_ajax_letters_get_json', 'letters_get_json');
function letters_get_json()
{
	// Edit letter
	$ID = (int) $_POST['ID'];

	try {
		$letter = email_get_TH($ID);
		$meta = json_decode($letter->MetaInfo);
		foreach ($meta->Attachments as $file) {
			if (!files_is_exists($file->path)) $file->error = 404;
			else $file->error = null;
		}

		$letter = array_merge((array) $letter, (array) $meta);
		echo json_encode(array(1, $letter));
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, 'Ошибка открытия письма'));
	}
	exit();
}

/**
 * Smart broadcast with delays
 */
function letters_broadcast_TH($type, $data)
{
	$rows = explode("\n", $data);
	global $wpdb, $CONFID;

	if ($type === 'Rooms') {
		$rows = db_list_cond('zi_ab_participants', 'ID_Conf=2', 'ID ASC');
		$sec1 = db_list_cond('zi_ab_rooms', 'SectionNo=1', 'ID ASC');
		$sec2 = db_list_cond('zi_ab_rooms', 'SectionNo=2', 'ID ASC');
		$sec3 = db_list_cond('zi_ab_rooms', 'SectionNo=3', 'ID ASC');
		$sec4 = db_list_cond('zi_ab_rooms', 'SectionNo=4', 'ID ASC');
	} else if ($type === 'Program' || $type === 'PosterRemind' || $type === 'PosterRemind2') {
		$rows = db_list_cond('zi_ab_participants', 'ID_Conf=2', 'ID ASC');
	} else if ($type === 'Comments') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');
		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$ecid = trim($lines[$i]);
			if (empty($ecid)) continue;

			$authors = $wpdb->get_results("
				SELECT au.ID
				FROM zi_ab_authors au JOIN zi_ab_articles ar ON ar.ID=au.ID_Article
				WHERE ar.ID_Conf=$CONFID AND ar.ECID=$ecid
			");

			foreach ($authors as $author) {
				$rows[] = (object) ['No' => $i + 1, 'ECID' => $ecid, 'ID_Author' => $author->ID];
			}
		}
	} else if ($type === 'Certificates') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');
		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			if (empty($lines[$i])) continue;

			$pars = explode("\t", $lines[$i]);
			$email = trim($pars[0]);
			$name = trim($pars[1]);
			$no = trim($pars[2]);

			$rows[] = (object) ['No' => $no, 'Name' => $name, 'EMail' => $email];
		}
	} else if ($type === 'Bests') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');
		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			if (empty($lines[$i])) continue;

			$pars = explode("\t", $lines[$i]);
			$email = trim($pars[1]);
			$name = trim($pars[2]);
			$no = trim($pars[0]);

			$rows[] = (object) ['No' => $no, 'Name' => $name, 'EMail' => $email];
		}
	} else if ($type === 'ExtPay') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			if (empty($lines[$i])) continue;

			$pars = explode("\t", $lines[$i]);
			$ecid = trim($pars[0]);
			$email = trim($pars[1]);
			$journal = trim($pars[2]);

			if ($journal === 'CEUR') {
				$journal = 'CEUR Workshop Proceedings';
				$sum = 1500;
			} else if ($journal === 'IEEE') {
				$journal = 'Proceedings IEEE';
				$sum = 6000;
			} else if ($journal === 'JoP') {
				$journal = 'Journal of Physics';
				$sum = 6000;
			} else {
				throw new Exception('Unexpected Journal');
			}

			$rows[] = (object) ['ECID' => $ecid, 'EMail' => $email, 'Journal' => $journal, 'Sum' => $sum];
		}
	} else if ($type === 'RSCI') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');
		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$ecid = trim($lines[$i]);
			if (empty($ecid)) continue;

			$authors = $wpdb->get_results("
				SELECT au.EMail
				FROM zi_ab_authors au JOIN zi_ab_articles ar ON ar.ID=au.ID_Article
				WHERE ar.ID_Conf=$CONFID AND ar.ECID=$ecid
			");

			foreach ($authors as $author) {
				$email = mb_strtolower($author->EMail);

				if (empty($email)) continue;

				// Exclude soifer
				if ($email === 'soifer@ssau.ru') continue;

				if (!in_array($email, $rows, true)) {
					$rows[] = mb_strtolower($email);
				}
			}
		}
	} else if ($type === 'Winner') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			if (empty($lines[$i])) continue;

			$rows[] = (object) ['EMail' => $lines[$i]];
		}
	} else if ($type === 'License') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$line = $lines[$i];
			if (empty($line)) continue;

			$pars = explode("\t", $line);
			if (sizeof($pars) !== 2) throw new Exception('Wrong number of parameters ' . sizeof($pars));

			$rows[] = (object) ['Title' => $pars[0], 'EMail' => $pars[1]];
		}
	} else if ($type === 'License2') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$line = $lines[$i];
			if (empty($line)) continue;

			$pars = explode("\t", $line);
			if (sizeof($pars) !== 3) {
				var_dump('Wrong number of parameters ' . sizeof($pars));
				throw new Exception('Wrong number of parameters ' . sizeof($pars));
			}

			$rows[] = (object) ['Title' => $pars[0], 'EMail' => $pars[1], 'RecEMail' => $pars[2]];
		}
	} else if ($type === 'ExtPay2') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$line = $lines[$i];
			if (empty($line)) continue;

			$pars = explode("\t", $line);
			if (sizeof($pars) !== 6) {
				var_dump('Wrong number of parameters ' . sizeof($pars));
				throw new Exception('Wrong number of parameters ' . sizeof($pars));
			}

			$journal = trim($pars[5]);

			if ($journal === 'CEUR') {
				$journal = 'CEUR Workshop Proceedings';
				$sum = 1500;
			} else if ($journal === 'IEEE') {
				$journal = 'Proceedings IEEE';
				$sum = 6000;
			} else if ($journal === 'JoP') {
				$journal = 'Journal of Physics';
				$sum = 6000;
			} else if ($journal === 'CO') {
				$journal = 'Computer Optics';
				$sum = 6000;
			} else {
				throw new Exception('Unexpected Journal');
			}

			$rows[] = (object) ['ECID' => $pars[0], 'Authors' => $pars[1], 'Title' => $pars[2], 'EMail' => $pars[3], 'RecEMail' => $pars[4], 'Journal' => $journal, 'Sum' => $sum];
		}
	} else if ($type === 'PartCont' || $type === 'ExtCont') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$line = $lines[$i];
			if (empty($line)) continue;

			$ecid = $line;

			$results = $wpdb->get_results("
				SELECT au.*
				FROM zi_ab_articles ar 
					JOIN zi_ab_authors au ON au.ID_Article=ar.ID
				WHERE ar.ID_Conf=2 AND ar.ECID=$ecid
			");

			foreach ($results as $author) {
				$rows[] = (object) ['ECID' => $ecid, 'EMail' => $author->EMail];
			}
		}
	} else if ($type === 'ExtPubConf') {
		$rows = [];
		$content = file_get_contents(get_template_directory() . '/emails/info.txt');

		$lines = explode("\n", $content);
		for ($i = 0; $i < sizeof($lines); $i++) {
			$line = $lines[$i];
			if (empty($line)) continue;

			$pars = explode("\t", $line);
			$ecid = $pars[0];
			$authors = $pars[1];
			$title = $pars[2];

			$results = $wpdb->get_results("
				SELECT au.*
				FROM zi_ab_articles ar 
					JOIN zi_ab_authors au ON au.ID_Article=ar.ID
				WHERE ar.ID_Conf=2 AND ar.ECID=$ecid AND au.IsCorr='Y'
			");

			foreach ($results as $author) {
				$rows[] = (object) ['ECID' => $ecid, 'EMail' => $author->EMail, 'Authors' => $authors, 'Title' => $title];
			}
		}
	}

	//Postponed sending			
	$chunk = 10;
	$delaySendSec = 5 * 60;
	$delayStartSec = 20 * 60;

	//Send messages
	for ($i = 0; $i < sizeof($rows); $i++) {
		$row = $rows[$i];
		$delaySeconds = round($i / $chunk) * $delaySendSec + $delayStartSec;

		switch ($type) {
			case 'PartConfirmation':
				letters_sendPartConfirmation_TH((int) $row, $delaySeconds);
				break;
			case 'PartConfirmationNoReg':
				$arr = explode(' ', $row);
				letters_sendPartConfirmationNoReg_TH((int) $arr[0], $arr[1], $delaySeconds);
				break;
			case 'Upload':
				letters_sendUploader_TH($row, $delaySeconds);
				break;
			case 'Registration':
				letters_sendRegConfirmation_TH($row, $delaySeconds);
				break;
			case 'Contacts':
				letters_sendContactsRequest_TH($row, $delaySeconds);
				break;
			case 'Rooms':
				letters_sendRooms_TH($row->Link, $sec1[$i]->Link, $sec2[$i]->Link, $sec3[$i]->Link, $sec4[$i]->Link, $delaySeconds);
				break;
			case 'Deadline':
				letters_sendDeadline_TH($row, $delaySeconds);
				break;
			case 'Program':
				letters_sendProgram_TH($row->Link, $delaySeconds);
				break;
			case 'PosterRemind':
				letters_sendPosterRemind_TH($row->Link, $delaySeconds);
				break;
			case 'PosterRemind2':
				letters_sendPosterRemind2_TH($row->Link, $delaySeconds);
				break;
			case 'Comments':
				letters_sendComments_TH($row->No, $row->ECID, $row->ID_Author, $delaySeconds);
				break;
			case 'Certificates':
				letters_sendCertificates_TH($row->No, $row->EMail, $row->Name, $delaySeconds);
				break;
			case 'Bests':
				letters_sendBests_TH($row->No, $row->EMail, $row->Name, $delaySeconds);
				break;
			case 'ExtPay':
				letters_sendExtPayment_TH($row->ECID, $row->EMail, $row->Journal, $row->Sum, $delaySeconds);
				break;
			case 'RSCI':
				letters_sendElibraryLink_TH($row, $delaySeconds);
				break;
			case 'Winner':
				letters_sendWinner_TH($row->EMail, $delaySeconds);
				break;
			case 'License':
				letters_sendLicense_TH($row->Title, $row->EMail, $delaySeconds);
				break;
			case 'License2':
				letters_sendLicense2_TH($row->Title, $row->EMail, $row->RecEMail, $delaySeconds);
				break;
			case 'ExtPay2':
				letters_sendExtPayment2_TH($row->ECID, $row->Authors, $row->Title, $row->EMail, $row->RecEMail, $row->Journal, $row->Sum, $delaySeconds);
				break;
			case 'PartCont':
				letters_sendPartCont_TH($row->ECID, $row->EMail, $delaySeconds);
				break;
			case 'ExtCont':
				letters_sendExtCont_TH($row->ECID, $row->EMail, $delaySeconds);
				break;
			case 'ExtPubConf':
				letters_sendExtPubConfirmation_TH($row->ECID, $row->Authors, $row->Title, $row->EMail, $delaySeconds);
				break;
		}
	}
}

/**
 * Smart broadcast with delays
 */
add_action('wp_ajax_letters_broadcast_json', 'letters_broadcast_json');
function letters_broadcast_json()
{
	$type = g_crd($_POST['Type']);
	$data = g_crv($_POST['Data']);

	//Send messages
	try {
		letters_broadcast_TH($type, $data);
		echo json_encode(array(1, 'Рассылка запущена'));
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, $e->getMessage()));
	}
	exit();
}

/**
 * Send letter
 */
add_action('wp_ajax_letters_send_json', 'letters_send_json');
function letters_send_json()
{
	$type = $_POST['Type'];

	$postponeSec = 5 * 60;
	//Send messages
	try {
		switch ($type) {
			case 'Scopus':
				letters_sendScopusConfirmation_TH($_POST['ECID'], $_POST['Authors'], $_POST['Title'], $_POST['EMail'], $postponeSec);
				break;
		}

		echo json_encode(array(1, 'Письмо запланировано к отправке'));
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, $e->getMessage()));
	}
	exit();
}
