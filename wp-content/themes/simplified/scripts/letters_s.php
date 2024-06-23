<?php

/**
 * Send restore letter
 */
function letters_sendRestore_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);
	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Restore Registration Edit Link';
	$text = "<p>If you need to edit your registration information, please, use the following link:<p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/registration/reg.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TONAME%', $toName, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%LINK%', get_site_url() . '/' . $link, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $link, 'Type' => 'Restore');

	email_add_TH($letter);
}


/**
 * Send registration confirmation letter
 */
function letters_sendRegConfirmation_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);
	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Registration Confirmation';

	$text = "
	<p>Thank you for registering to ITNT-2020 Conference!</p>
	<p>Please find attached a receipt for paying the conference fee.</p>
	<p>In the current situation, the Organizing Committee humbly asks you to make a payment as fast as possible (ideally before May 1, 2020). The deadline for payments is May 22, 2020.</p>
	<p>Thanks in advance for your understanding.</p>
	<br/>
	<p>If you need to edit your registration information, please, use the following link:<p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/registration/reg.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TONAME%', $toName, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%LINK%', get_site_url() . '/' . $link, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createInvitationPDF_TH($link), createPaymentPDF_TH($link));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $link, 'Type' => 'RegConf');

	email_add_TH($letter);
}

/**
 * Send E-mail notification of checked social events
 */
function letters_sendEvents_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);
	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Культурная программа';

	$events = form_get_reg_events($part->ID);
	//No events were selected
	if (empty($events)) return;

	load_textdomain('srvru', get_template_directory() . '/languages/ru_RU.mo');

	$sum = 0;
	$no = 1;
	$text = "";
	foreach ($events as $id => $status) {
		$event = db_get('zi_ab_events', $id);
		if ($event->Sum === '0') $valtext = '(бесплатно)';
		else $valtext = "({$event->Sum} руб.)";
		$title = _gl($event->Title, 'srvru');
		$text .= "$no) $title $valtext<br/>";

		$sum += $event->Sum;
		$no += 1;
	}

	$textHtml = @file_get_contents(get_template_directory() . '/emails/registration/events.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TONAME%', $toName, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%LINK%', get_site_url() . '/' . $link, $textHtml);
	$textHtml = str_replace('%SUM%', $sum, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'Ответственный секретарь ITNT-2020', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $link);

	email_add_TH($letter);
}


/**
 * Send letter with a link to file uploader (ignores listeners)
 */
function letters_sendUploader_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);
	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$papers = form_get_reg_articles($part->ID);

	if (!sizeof($papers)) return;

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Upload';

	$linkWord = sizeof($papers) > 1 ? "links" : "link";

	$text = "<p>The Editorial Board of ITNT-2020 has allocated individual cloud storage space for your video/poster presentation.</p> 
	<p>A detailed manual on recording a video presentation is available on our website:</p>
	<p><a href='http://itnt-conf.org/index.php/en/materials/manuals'>http://itnt-conf.org/index.php/en/materials/manuals</a></p><br/>
	<p>To upload all required materials, please, use the following $linkWord:</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/registration/upload.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TONAME%', $toName, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Tech', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$no = 1;
	foreach ($papers as $row) {
		$upload = db_get('zi_ab_uploads', $row->ECID);

		if (is_null($upload)) throw new DataException('URL не найден', array('function' => __FUNCTION__, 'value' => $row->ECID));

		$papersStr = '<b>' . $row->ECID . ':</b> ' . contractAuthors($row->Authors) . ' "' . $row->Title . '"';

		$textHtml = str_replace("%PAPER{$no}%", $papersStr, $textHtml);
		$textHtml = str_replace("%ID{$no}%", $row->ECID, $textHtml);
		$textHtml = str_replace("%LINK{$no}%", $upload->URL, $textHtml);

		$no++;
	}
	if (sizeof($papers) === 1) {
		$textHtml = preg_replace('/<!--PAPER2-->.*?<!--PAPER2-->/s', '', $textHtml);
	}

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $link, 'Type' => 'Upload', 'Tech' => 'Y');

	email_add_TH($letter);
}


/**
 * Send access to online rooms
 */
function letters_sendRooms_TH($link, $sec1, $sec2, $sec3, $sec4, $postponeSec)
{
	$part = form_get_reg_by_link($link);
	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Online Sessions';

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/rooms.html');
	$textHtml = str_replace('%LINK1%', $sec1, $textHtml);
	$textHtml = str_replace('%LINK2%', $sec2, $textHtml);
	$textHtml = str_replace('%LINK3%', $sec3, $textHtml);
	$textHtml = str_replace('%LINK4%', $sec4, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Tech', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $link, 'Type' => 'Rooms', 'Tech' => 'Y');

	email_add_TH($letter);
}

/**
 * Send letter with contacts request
 */
function letters_sendContactsRequest_TH($ecid, $postponeSec)
{
	global $wpdb;
	$part = $wpdb->get_row("
		SELECT *
		FROM zi_ab_participants p
			JOIN zi_ab_arlinks al ON al.ID_Participant = p.ID
			JOIN zi_ab_articles a ON a.ID = al.ID_Article
		WHERE a.ECID=$ecid AND p.ID_Conf=2
	");

	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $ecid));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Conference';

	$text = "<p>Уважаемый докладчик!</p>
	<p>Ваш доклад ID:$ecid \"{$part->Title}\" был зарегистрирован c представлением в форме онлайн-выступления на секции \"Науки о данных\". Ваш доклад состоится во вторник 26 мая. Предположительное время работы секции: с 13.00 до 19.00.</p> 
	<p>Просим вас подтвердить возможность участия в онлайн-сессии ответным письмом на tech@itnt-conf.org, а также прислать контактные данные для оперативной связи в случае технических накладок, переносов и прочих незапланированных ситуаций. В случае, если вам предпочтителен (или категорически не подходит) какой-либо конкретный временной промежуток в этих пределах, также напишите об этом в ответном письме. Мы не можем гарантировать, но сделаем всё возможное, чтобы  составить программу максимально удобным для вас образом.</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Tech', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $part->Link, 'Type' => 'Contacts', 'Tech' => 'Y');

	email_add_TH($letter);
}

/**
 * Send letter with last deadline
 */
function letters_sendDeadline_TH($ecid, $postponeSec)
{
	global $wpdb;
	$part = $wpdb->get_row("
		SELECT *
		FROM zi_ab_participants p
			JOIN zi_ab_arlinks al ON al.ID_Participant = p.ID
			JOIN zi_ab_articles a ON a.ID = al.ID_Article
		WHERE a.ECID=$ecid AND p.ID_Conf=2
	");

	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $ecid));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Conference';

	$text = "<p>Уважаемый участник конференции!</p>
	<p>Ваш доклад ID:$ecid \"{$part->Title}\" был зарегистрирован c представлением в форме онлайн-выступления на секции \"Науки о данных\". Онлайн-сессия состоится во вторник, 26 мая, предположительно с 13.00 до 18.00 по Самарскому времени (GMT+4).</p>
	<p>Просим вас подтвердить возможность участия в онлайн-сессии (если вы ещё этого не сделали) ответным письмом на tech@itnt-conf.org, а также <b>прислать контактные данные для оперативной связи</b> в случае технических накладок, переносов и прочих незапланированных ситуаций. В случае, если вам предпочтителен (или категорически не подходит) какой-либо конкретный временной промежуток в этих пределах, также напишите об этом в ответном письме. Мы не можем гарантировать, но сделаем всё возможное, чтобы  составить программу максимально удобным для вас образом.</p>
	<p>Для докладчиков, подтвердивших своё участие, будет сформировано и разослано расписание выступлений. Не подтверждённые <b>сегодня (25 мая) до 16.00 по Самарскому времени (GMT+4)</b> доклады будут переведены в формат записанных видео или постеров (в зависимости от загруженных материалов).</p> 
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Tech', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $part->Link, 'Type' => 'Deadline', 'Tech' => 'Y');

	email_add_TH($letter);
}

/**
 * Send letter with program
 */
function letters_sendProgram_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);

	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $ecid));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Conference';

	$addr = 'http://itnt-conf.org/index.php/en/online/conference-program';


	$text = "<p>Dear $toName,</p>
	<p>The actual Program of ITNT-2020 is finally published on our website:</p>
	<br/>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blankwbtn.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%LINK%', $addr, $textHtml);
	$textHtml = str_replace('%BTNTEXT%', 'Conference Program', $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $part->Link, 'Type' => 'Program');

	email_add_TH($letter);
}

/**
 * Send poster reminder
 */
function letters_sendPosterRemind_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);

	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Conference';

	$text = "<p>Уважаемые коллеги!</p>
	<p>Благодарим Вас за участие в ИТНТ-2020!</p>
	<p>
		<div>Напоминаем, что на протяжении всех 4-х дней (с 26 по 29 мая) в рамках нашей Конференции работают секции Видеодеопрезентаций и Постеров. Ссылки на представленные в них доклады Вы можете найти в Программе Конференции:</div>
		<div><a href='http://itnt-conf.org/images/program-ITNT-2020.pdf' target='_blank'>http://itnt-conf.org/images/program-ITNT-2020.pdf</a></div>
	</p>
	<p>
		<div>Кроме того, все доклады также представлены на сайте мероприятия в соответствующих разделах Меню ONLINE:</div>
		<div><a href='http://itnt-conf.org/index.php' target='_blank'>http://itnt-conf.org/index.php</a></div>
	</p>
	<br/>
	<p>Приглашаем Вас ознакомиться с работами других участников Конференции и задать им свои вопросы в комментариях или же посредством формы обратной связи на сайте.</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $part->Link, 'Type' => 'PosterRemind');

	email_add_TH($letter);
}

/**
 * Send poster reminder2
 */
function letters_sendPosterRemind2_TH($link, $postponeSec)
{
	$part = form_get_reg_by_link($link);

	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $link));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2020 Conference';

	$text = "<p>Уважаемые участники!</p>
	<p>Конференция ИТНТ-2020 - в самом разгаре! Ведущие учёные собирают широкую аудиторию для обсуждения наиболее актуальных проблем в рамках пленарных выступлений, в то время как секционные доклады удивляют невероятно высоким качеством работ.</p>
	<p>Если вдруг по каким-то причинам Вы пропустили онлайн-секцию Конференции - не расстраивайтесь, специально для Вас Организационный комитет ИТНТ-2020 выложил видеозаписи выступлений на своём <b>YouTube</b> канале, где Вы можете ознакомиться с докладами и задать авторам интересующие Вас вопросы в комментариях под роликом. Все ссылки на видеозаписи представлены на сайте Конференции в разделах <b>\"Онлайн Выступления\"</b> и <b>\"Видеопрезентации\"</b>.</p>
	<p>Не осталась в стороне и постерная сессия, которая переживает небывалый наплыв участников: одни постеры буквально завалены вопросами, тогда как другие замерли в ожидании заинтересованного зрителя. Если Вы ещё не успели ознакомиться с постерной сессией ИТНТ-2020 на платформе <b>miro</b> и задать свои вопросы авторам докладов - самое время это сделать! Ссылки на стендовые доклады также представлены на сайте Конференции в разделе <b>\"Постеры\"</b>.</p>
	<br/>
	<p>В свою очередь, Организационный комитет ИТНТ-2020 очень просит авторов секционных и постерных сессий регулярно просматривать странички со своими докладами на <b>YouTube</b> или <b>miro</b> и не оставлять вопросы коллег без внимания.</p>
	<br/>
	<p>Желаем Вам продуктивного общения!</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $part->Link, 'Type' => 'PosterRemind');

	email_add_TH($letter);
}

/**
 * Contract authors to J.U. Smith
 */
function contractAuthors($str)
{
	$result = '';
	$str = str_replace(' and ', ', ', $str);
	$authors = explode(', ', $str);
	foreach ($authors as $author) {
		if (!empty($result)) $result .= ', ';
		//Get F,I,O
		$parts = explode(' ', $author);
		for ($i = 0; $i < sizeof($parts); $i++) {
			$part = $parts[$i];
			if ($i !== sizeof($parts) - 1) $result .= mb_substr($part, 0, 1) . '.';
			else {
				if ($i !== 0) $result .= " ";
				$result .= $part;
			}
		}
	}
	return $result;
}

/**
 * Fill in invitation PDF
 */
function createInvitationPDF_TH($link)
{
	$reg = form_get_reg_by_link($link);
	$papers = form_get_reg_articles($reg->ID);

	$replaces = array();

	//Listener
	if (empty($papers))
		$pdfhtml = get_template_directory() . '/emails/registration/listener_pdf.html';
	//Presenter
	else {
		$pdfhtml = get_template_directory() . '/emails/registration/presenter_pdf.html';

		$no = 1;
		foreach ($papers as $row) {
			$papersStr = $row->ECID . ': ' . contractAuthors($row->Authors) . ' "' . $row->Title . '"';
			if ($no === sizeof($papers)) $papersStr .= '.';
			else $papersStr .= ',';

			$replaces["%PAPER{$no}%"] = $papersStr;
			$no++;
		}
		for ($no; $no <= 2; $no++) {
			$replaces["%PAPER{$no}%"] = "&nbsp;";
		}
	}
	$replaces['%TONAME%'] = $reg->FirstName . ' ' . $reg->LastName;

	$name = 'Invitation_' . sprintf("%03d", $reg->ID);

	$res = files_fill_pdf_TH($pdfhtml, $replaces, $name);
	return $res;
}

/**
 * Fill in invitation PDF
 */
function createPaymentPDF_TH($link)
{
	$reg = form_get_reg_by_link($link);
	$papers = form_get_reg_articles($reg->ID);

	$replaces = array();

	$pdfhtml = get_template_directory() . '/emails/payments/fee_pdf.html';
	$id = "";
	$sum = 0;

	//Listener
	if (empty($papers)) {
		$id = "Cлушатель";
		$sum = 1000;
	}
	//Presenter
	else {
		$fee = $reg->SciStatus === "S" ? 1000 : 4000;

		foreach ($papers as $row) {
			if (!g_ies($id)) $id .= ', ';
			$id .= $row->ECID;
			$sum += $fee;
		}
	}

	$replaces['%ID%'] = $id;
	$replaces['%SUM%'] = $sum;

	$name = 'Fee_' . sprintf("%03d", $reg->ID);

	$res = files_fill_pdf_TH($pdfhtml, $replaces, $name);
	return $res;
}

/**
 * Send participation confirmation letter
 */
function letters_sendPartConfirmation_TH($ID_Article, $postponeSec)
{
	$ID_Participant = db_list_cond('zi_ab_arlinks', "ID_Article=$ID_Article")[0]->ID_Participant;
	$part = db_get('zi_ab_participants', $ID_Participant);
	if (is_null($part)) throw new DataException('Участник не найден', array('function' => __FUNCTION__, 'value' => $ID_Article));

	$toName = $part->Honorific . ' ' . $part->FirstName . ' ' . $part->LastName;
	$toEmail = $part->EMail;
	$subject = 'ITNT-2019 Подтверждение участия';

	$textHtml = @file_get_contents(get_template_directory() . '/emails/confirmations/partconfirm.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2019 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createPartConfirmationPDF_TH($ID_Article));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Link' => $part->Link);

	email_add_TH($letter);
}

/**
 * Send participation confirmation letter to non-registered participant
 */
function letters_sendPartConfirmationNoReg_TH($ID_Article, $toEmail, $postponeSec)
{
	$toName = 'участник';
	$toEmail = $toEmail;
	$subject = 'ITNT-2019 Подтверждение участия';

	$textHtml = @file_get_contents(get_template_directory() . '/emails/confirmations/partconfirm.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2019 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createPartConfirmationPDF_TH($ID_Article));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array();

	email_add_TH($letter);
}

/**
 * Fill in participation confirmation PDF
 */
function createPartConfirmationPDF_TH($ID_Article)
{
	global $FILESFOLDER;

	$article = db_get('zi_ab_articles', $ID_Article);
	$pdfhtml = get_template_directory() . '/emails/confirmations/partconfirm_pdf.html';

	$replaces = array();
	$replaces['%ID%'] = $article->ECID;
	$replaces['%AUTHORS%'] = $article->Authors;
	$replaces['%PAPER%'] = $article->Title;
	$replaces['%SIGNPIC%'] = $FILESFOLDER . 'ini/signk.png';

	if ($article->Decision === 'ACCEPT ORAL')
		$replaces['%FORMAT%'] = 'устный доклад';
	else if ($article->Decision === 'ACCEPT POSTER')
		$replaces['%FORMAT%'] = 'стендовый доклад';
	else
		throw new DataException('Формат не определён', array('function' => __FUNCTION__, 'value' => $ID_Article));

	$name = 'PartConf_' . sprintf("%03d", $article->ECID);

	$res = files_fill_pdf_TH($pdfhtml, $replaces, $name);
	return $res;
}


/**
 * Send comments on extended versions of papers
 */
function letters_sendComments_TH($no, $ecid, $ID_Author, $postponeSec)
{
	global $CONFID;

	$title = db_list_cond('zi_ab_articles', "ID_Conf=$CONFID AND ECID=$ecid")[0]->Title;

	$author = db_get('zi_ab_authors', $ID_Author);

	$toName = $author->FirstName . ' ' . $author->LastName;
	$toEmail = $author->EMail;
	$subject = 'ITNT-2020 Papers';

	$text = "<p>Уважаемые авторы!</p>
	<p>Просим ознакомиться с результатами рецензирования и проверки вашей работы #$ecid - \"$title\". Хотим обратить ваше внимание, что окончательное решение о включении работ в сборники будет принято Программным комитетом после 29 июня по результатам проверки на наличие плагиата и дополнительного рецензирования (при наличии замечаний в настоящей рецензии).</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Оргкомитет конференции ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$comments = getFile("coms/com $no.pdf");
	$comments['name'] = "$ecid.pdf";
	$letter['Attachments'] = array($comments);
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'Coms');

	email_add_TH($letter);
}


/**
 * Send certificates
 */
function letters_sendCertificates_TH($no, $email, $name, $postponeSec)
{
	$toName = $name;
	$toEmail = $email;
	$subject = 'ITNT-2020 Certificate';

	$text = "<p>Dear participant,</p>
	<p>Please find attached a Certificate of Attendance for the ITNT-2020.</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$cert = getFile("certs/$no.pdf");
	$cert['name'] = "Certificate.pdf";
	$letter['Attachments'] = array($cert);
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'Cert');

	email_add_TH($letter);
}


/**
 * Send best paper awards
 */
function letters_sendBests_TH($no, $email, $name, $postponeSec)
{
	$toName = $name;
	$toEmail = $email;
	$subject = 'ITNT-2020 Best Paper';

	$text = "<p>Dear participant,</p>
	<p>Please find attached a Best Paper Award Certificate for your paper at the ITNT-2020 conference.</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => $toName, 'Email' => $toEmail));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$cert = getFile("bests/BPA $no.png");
	$cert['name'] = "BestPaper.png";
	$letter['Attachments'] = array($cert);
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'Cert');

	email_add_TH($letter);
}


/**
 * Send scopus publication confirmation letter
 */
function letters_sendScopusConfirmation_TH($ecid, $authors, $title, $email, $postponeSec)
{
	$subject = 'ITNT-2020 Publication Confirmation';
	$textHtml = @file_get_contents(get_template_directory() . '/emails/confirmations/pubconfirm.html');
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createConfirmationScopusPDF_TH($ecid, $authors, $title));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'ScopusConf');

	email_add_TH($letter);
}


/**
 * Send elibrary link letter
 */
function letters_sendElibraryLink_TH($email, $postponeSec)
{
	$subject = 'ITNT-2020 RSCI Proceedings';
	$text = "<p>Уважаемый автор!</p>
	<p>Рады сообщить о размещении сборников материалов VI&nbsp;Международной конференции и молодёжной школы «Информационные технологии и нанотехнологии» (ИТНТ-2020) на платформе e-library (РИНЦ).</p>
	<p>
	Сборники доступны по ссылкам:
	</p>
	<p style='text-align:left'>
	<a href='https://www.elibrary.ru/item.asp?id=43821327' target='_blank'>Том 1. Компьютерная оптика и нанофотоника</a>
	</p>
	<p style='text-align:left'>
	<a href='https://www.elibrary.ru/item.asp?id=43785320' target='_blank'>Том 2. Обработка изображений и дистанционное зондирование Земли</a>
	</p>
	<p style='text-align:left'>
	<a href='https://www.elibrary.ru/item.asp?id=43818342' target='_blank'>Том 3. Математическое моделирование физико-технических процессов и систем</a>
	</p>
	<p style='text-align:left'>
	<a href='https://www.elibrary.ru/item.asp?id=43570226' target='_blank'>Том 4. Науки о данных</a>
	</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'RSCI');

	email_add_TH($letter);
}

/**
 * Send no payment for winners
 */
function letters_sendWinner_TH($email, $postponeSec)
{
	$subject = 'ITNT-2020 Extended Papers';
	$text = "<p>Уважаемый автор!</p>
	<p>Вы были признаны призёром VI Международной конференции и молодёжной школы «Информационные технологии и нанотехнологии» (ИТНТ-2020).</p>
	<p>Рады сообщить, что в качестве приза Вы получаете бесплатную публикацию в расширенном сборнике конференции.</p>
	<p>Если Вам приходила квитанция на оплату размещения работы в сборнике, оплату производить <u><b>не
требуется!</b></u>
	</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'RSCI');

	email_add_TH($letter);
}

/**
 * Send license request
 */
function letters_sendLicense_TH($title, $email, $postponeSec)
{
	$subject = 'ITNT-2020 Лицензионное соглашение';
	$text = "<p>Уважаемый автор!</p>
	<p>12.10.2020 Вам было направлено письмо со ссылкой на сайт IEEE для подписи лицензионного соглашения о публикации Вашей статьи:<br/>
	\"$title\".</p>
	<p>В процессе подписания Вы соглашаетесь с передачей прав на публикацию IEEE, а также подтверждаете, что имеете право подписать соглашение от лица всего авторского коллектива. Кроме того, при желании, Вы можете предоставить возможность IEEE использовать запись Вашего выступления.</p>
	<p>Оргкомитет убедительно просит Вас подписать лицензионное соглашение, заполнив все необходимые формы, в срок <b>до 16:00 15.10.2020</b>. От этого зависят сроки публикации сборника.</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'License');

	email_add_TH($letter);
}

/**
 * Send license request2
 */
function letters_sendLicense2_TH($title, $email, $recemail, $postponeSec)
{
	$subject = 'ITNT-2020 Лицензионное соглашение';
	$text = "<p>Уважаемый автор!</p>
	<p>22.10.2020 на почту <b>$recemail</b> было повторно направлено письмо со ссылкой на сайт IEEE для подписи лицензионного соглашения о публикации Вашей статьи:<br/>
	\"$title\".</p>
	<p>В процессе подписания Вы соглашаетесь с передачей прав на публикацию IEEE, а также подтверждаете, что имеете право подписать соглашение от лица всего авторского коллектива. Кроме того, при желании, Вы можете предоставить возможность IEEE использовать запись Вашего выступления.</p>
	<p>Оргкомитет убедительно просит Вас подписать лицензионное соглашение, заполнив все необходимые формы, в срок <b>до 18:00 23.10.2020 (GMT+4)</b>. К сожалению, если до означенной даты лицензионное соглашение подписано не будет, то мы будем <b>вынуждены перенести Вашу статью в другой сборник</b>.</p>
	<p>К письму прилагаем краткую инструкцию по заполнению форм.</p>
	<br/>
	<p>Если указанное письмо отсутствует в списке полученных, пожалуйста, проверьте папку \"Спам\".<br/>
	Тема письма: <b>IEEE Copyright Transfer Confirmation for Article:...</b><br/>
	Отправитель: <b>ecopyright@ieee.org</b>
	</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$tutorial = getFile("tutorial.pdf");
	$tutorial['name'] = "Tutorial IEEE.pdf";
	$letter['Attachments'] = array($tutorial);
	$letter['Extra'] = array('Type' => 'License2');

	email_add_TH($letter);
}




/**
 * Fill in confirmation PDF
 */
function createConfirmationScopusPDF_TH($ecid, $authors, $title)
{
	$replaces = [
		"%ID%" => $ecid,
		"%AUTHORS%" => $authors,
		"%PAPER%" => $title
	];

	$pdfhtml = get_template_directory() . '/emails/confirmations/pubconfirm2_pdf.html';

	$name = 'ConfScopus_' . sprintf("%03d", $ecid);

	$res = files_fill_pdf_TH($pdfhtml, $replaces, $name);
	return $res;
}




/**
 * Send extended paper payment request
 */
function letters_sendExtPayment_TH($ecid, $email, $journal, $sum, $postponeSec)
{
	$subject = 'ITNT-2020 Extended Papers';

	$textHtml = @file_get_contents(get_template_directory() . '/emails/payments/extended.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%ID%', $ecid, $textHtml);
	$textHtml = str_replace('%JOURNAL%', $journal, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=["\']\.\..*?["\']/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createExtPaymentPDF_TH($ecid, $sum));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'ExtPay');

	email_add_TH($letter);
}




/**
 * Send extended paper payment request2
 */
function letters_sendExtPayment2_TH($ecid, $authors, $title, $email, $recemail, $journal, $sum, $postponeSec)
{
	$subject = 'ИТНТ-2020 Оплата публикации';

	$text = "<p>Уважаемый автор!</p>
	<p>15.09.2020 на почту <b>$recemail</b> было направлено письмо об оплате публикации Вашей статьи:<br/> 
	ID:$ecid $authors \"$title\"<br/> 
	по результатам ИТНТ-2020 в сборнике	расширенных версий работ $journal. Однако, оплата по данной статье до сегодняшнего дня не была проведена.</p>
	<p>Оргкомитет убедительно просит Вас оплатить публикацию расширенной статьи в срок <b>до 16:00 05.11.2020 (GMT+4)</b>. В случае неоплаты / несвоевременной оплаты <b>статья будет исключена из сборника</b>. По факту оплаты просим ответным письмом выслать <b>электронную копию чека</b>.</p>
	<p>К письму прилагаем квитанцию для оплаты.</p>
	<br/>
	<p>P.S. Данная рассылка осуществлена по всем соавторам статьи. Просим избегать дублирования оплаты.</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = 'Срочно! ' . $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createExtPaymentPDF_TH($ecid, $sum));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'ExtPay2');

	email_add_TH($letter);
}

/**
 * Send participation contract reminder
 */
function letters_sendPartCont_TH($ecid, $email, $postponeSec)
{
	$subject = 'ИТНТ-2020 Оргвзнос (договор)';

	$text = "<p>Уважаемый автор!</p>
	<p>Для отчетности Оргкомитету необходимы оригиналы следующих подписанных документов: договора на оплату оргвзноса за участие в конференции по статье ID:$ecid и акта по данному договору.</p>
	<p>Скачать их шаблоны, а также ознакомиться с примером заполнения, можно по адресу: <a target='_blank' href='http://itnt-conf.org/index.php/uchastnikam/oplata/orgvznosy-na-konferentsiyu'>http://itnt-conf.org/index.php/uchastnikam/oplata/orgvznosy-na-konferentsiyu</a></p>
	<p>Отмечаем, что:<br>
	1) договор заключается автором, который производил оплату;<br>
	2) на каждую статью оформляется только один договор;<br>
	3) в одном договоре можно перечислить несколько работ (при условии, что оплата производилась одним и тем же автором).
	</p>
	<p>Оргкомитет убедительно просит Вас <b>скачать</b> эти документы, <b>заполнить</b>, <b>подписать</b> с Вашей стороны и <b><u>прислать</u></b> почтой (лучше обычным, а не заказным письмом) на адрес Оргкомитета: <b>Козловой Е.С.</b> ИСОИ РАН – филиал ФНИЦ «Кристаллография и фотоника» РАН Россия, 443001, Самара, ул. Молодогвардейская, 151 с пометкой в <b>Оргкомитет ИТНТ-2020</b>, предварительно выслав скан-копии этих документов на почту: s-m-r-l@yandex.ru. По всем вопросам, касающимся договоров, просим обращаться по электронной почте: s-m-r-l@yandex.ru.</p>
	<br/>
	<p>P.S. Данная рассылка осуществляется по всем авторам статьи. Просим избегать дублирования документов.</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = 'Срочно! ' . $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'PartCont');

	email_add_TH($letter);
}

/**
 * Send extention papers contract reminder
 */
function letters_sendExtCont_TH($ecid, $email, $postponeSec)
{
	$subject = 'ИТНТ-2020 Публикация (договор)';

	$text = "<p>Уважаемый автор!</p>
	<p>Для отчетности Оргкомитету необходимы оригиналы следующих подписанных документов: договора на оплату публикации расширенной англоязычной версии статьи ID:$ecid и акта по данному договору.</p>
	<p>Скачать их шаблоны, а также ознакомиться с примером заполнения, можно по адресу: <a target='_blank' href='http://itnt-conf.org/index.php/uchastnikam/oplata/extrapaper-pay'>http://itnt-conf.org/index.php/uchastnikam/oplata/extrapaper-pay</a></p>
	<p>Отмечаем, что:<br>
	1) договор заключается автором, который производил оплату;<br>
	2) на каждую статью оформляется только один договор;<br>
	3) в одном договоре можно перечислить несколько работ (при условии, что оплата производилась одним и тем же автором).
	</p>
	<p>Оргкомитет убедительно просит Вас <b>скачать</b> эти документы, <b>заполнить</b>, <b>подписать</b> с Вашей стороны и <b><u>прислать</u></b> почтой (лучше обычным, а не заказным письмом) на адрес Оргкомитета: <b>Козловой Е.С.</b> ИСОИ РАН – филиал ФНИЦ «Кристаллография и фотоника» РАН Россия, 443001, Самара, ул. Молодогвардейская, 151 с пометкой в <b>Оргкомитет ИТНТ-2020</b>, предварительно выслав скан-копии этих документов на почту: s-m-r-l@yandex.ru. По всем вопросам, касающимся договоров, просим обращаться по электронной почте: s-m-r-l@yandex.ru.</p>
	<br/>
	<p>P.S. Данная рассылка осуществляется по всем авторам статьи. Просим избегать дублирования документов.</p>
	<br/>
	<p>
		<div>С уважением,</div>
		<div>Организационный комитет ИТНТ-2020</div>
	</p>";

	$textHtml = @file_get_contents(get_template_directory() . '/emails/info/blank.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%TEXT%', $text, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = 'Срочно! ' . $subject;
	$letter['Text'] = $textHtml;
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'PartCont');

	email_add_TH($letter);
}



/**
 * Fill in extended paper payment request PDF
 */
function createExtPaymentPDF_TH($ecid, $sum)
{
	$replaces = [
		"%ID%" => $ecid,
		"%SUM%" => $sum
	];

	$pdfhtml = get_template_directory() . '/emails/payments/extendedZ_pdf.html';

	$name = 'ExtPay_' . sprintf("%03d", $ecid);

	$res = files_fill_pdf_TH($pdfhtml, $replaces, $name);
	return $res;
}



/**
 * Send extended publication confirmation letter
 */
function letters_sendExtPubConfirmation_TH($ecid, $authors, $title, $email, $postponeSec)
{
	$subject = 'ITNT-2020 Extended Papers';

	$textHtml = @file_get_contents(get_template_directory() . '/emails/confirmations/pubconfirm.html');
	$textHtml = str_replace('%TITLE%', $subject, $textHtml);
	$textHtml = str_replace('%FOOTER%', 'ITNT-2020 Secretary', $textHtml);
	$textHtml = preg_replace('/src=\'\.\..*?\'/', '', $textHtml);
	$textHtml = str_replace('%LOGOPIC%', get_template_directory_uri() . '/emails/logo.png', $textHtml);

	$letter = array();
	$letter['TO'] = array(array('Name' => '', 'Email' => $email));
	$letter['Subject'] = $subject;
	$letter['Text'] = $textHtml;
	$letter['Attachments'] = array(createExtPubConfirmationPDF_TH($ecid, $authors, $title));
	$letter['Postpone'] = $postponeSec . 's';
	$letter['Extra'] = array('Type' => 'ExtPubConf');

	email_add_TH($letter);
}



/**
 * Fill in extended publication confirmation PDF
 */
function createExtPubConfirmationPDF_TH($ecid, $authors, $title)
{
	global $FILESFOLDER;

	$pdfhtml = get_template_directory() . '/emails/confirmations/pubconfirm_pdf.html';

	$replaces = array();
	$replaces['%ID%'] = $ecid;
	$replaces['%AUTHORS%'] = $authors;
	$replaces['%PAPER%'] = $title;
	$replaces['%SIGNPIC%'] = $FILESFOLDER . 'ini/signk.png';

	$name = 'ExtConf_' . sprintf("%03d", $ecid);

	$res = files_fill_pdf_TH($pdfhtml, $replaces, $name);
	return $res;
}
