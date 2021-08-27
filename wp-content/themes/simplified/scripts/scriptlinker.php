<?php

date_default_timezone_set('Europe/Samara');

include_once __DIR__ . '/general_s.php';
include_once __DIR__ . '/dbhandler_s.php';

include_once __DIR__ . '/form_s.php';
include_once __DIR__ . '/easychair_s.php';
include_once __DIR__ . '/files_s.php';
include_once __DIR__ . '/letters_s.php';
include_once __DIR__ . '/letterfactory_s.php';

include_once __DIR__ . '/plugins/email/PHPMailer/src/Exception.php';
include_once __DIR__ . '/plugins/email/PHPMailer/src/PHPMailer.php';
include_once __DIR__ . '/plugins/email/PHPMailer/src/SMTP.php';
include_once __DIR__ . '/plugins/email/email_s.php';

include_once __DIR__ . '/plugins/pdf/pdf_s.php';