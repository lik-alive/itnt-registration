<?php

//-----Account functions

//Check current user access among the list
function g_cua(...$users)
{
	foreach ($users as $user) {
		if (current_user_can($user)) return true;
	}
	return false;
}

//-----String functions

//Clear request data
function g_crd($arr)
{
	//Mass strip slashes (all NULL values removed, all '' values become null, all 'null' values become null)
	foreach ($arr as $key => $val) {
		if (is_array($val)) $arr[$key] = g_crd($val);
		else {
			$arr[$key] = g_crv($val);
		}
	}

	return $arr;
}

//Clear request value
function g_crv($value)
{
	//Null to null
	if (is_null($value)) return null;

	//Empty string to null
	if (g_ies($value)) return null;

	//'Null' string to null
	if ('null' === $value) return null;

	//Strip slashes
	return trim(stripslashes($value));
}

//Is empty string
function g_ies($str)
{
	return ('' === trim($str));
}

//Is Russian char
function g_irc($c)
{
	$rus = "абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
	return mb_strpos($rus, $c) !== false;
}

//Is English char
function g_iec($c)
{
	$eng = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	return mb_strpos($eng, $c) !== false;
}

//Check if string is mostly in russian
function g_sir($str)
{
	$rus = array();
	preg_match('/[а-яА-ЯёЁ ]+/', $str, $rus);
	$eng = array();
	preg_match('/[a-zA-Z ]+/', $str, $eng);
	$maxrus = 0;
	foreach ($rus as $one) {
		if (mb_strlen($one) > $maxrus) $maxrus = mb_strlen($one);
	}
	$maxeng = 0;
	foreach ($eng as $one) {
		if (mb_strlen($one) > $maxeng) $maxeng = mb_strlen($one);
	}
	return $maxrus > $maxeng;
}

//Change keyboard layout
function g_ckl($data)
{
	$str_rus = array(
		"й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ",
		"ф", "ы", "в", "а", "п", "р", "о", "л", "д", "ж", "э",
		"я", "ч", "с", "м", "и", "т", "ь", "б", "ю", "ё",
		"Й", "Ц", "У", "К", "Е", "Н", "Г", "Ш", "Щ", "З", "Х", "Ъ",
		"Ф", "Ы", "В", "А", "П", "Р", "О", "Л", "Д", "Ж", "Э",
		"Я", "Ч", "С", "М", "И", "Т", "Ь", "Б", "Ю", "Ё"
	);
	$str_eng = array(
		"q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "[", "]",
		"a", "s", "d", "f", "g", "h", "j", "k", "l", ";", "'",
		"z", "x", "c", "v", "b", "n", "m", ",", ".", "`",
		"Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "{", "}",
		"A", "S", "D", "F", "G", "H", "J", "K", "L", ":", "\"",
		"Z", "X", "C", "V", "B", "N", "M", "<", ">", "?"
	);
	if (g_sir($data)) $revert = str_replace($str_rus, $str_eng, $data);
	else $revert = str_replace($str_eng, $str_rus, $data);
	return $revert;
}

//Return localized string. Different forms of the same word can be represented as "other" and "other_"
function _gl($text, $domain = 'translate')
{
	$str = __($text, $domain);
	$str = str_replace('_', '', $str);
	return $str;
}

//Echo localized string. Different forms of the same word can be represented as "other" and "other_"
function _ge($text, $domain = 'translate')
{
	$text = str_replace("&nbsp;", ' ', $text);
	$str = __($text, $domain);
	$str = str_replace('_', '', $str);
	echo $str;
}

//-----Logging

//Log event
function g_lev($message, $function, $value = null)
{
	$message = "$message <$function>";
	files_write_log('INFO', $message, $value);
}

//Log DB event
function g_ldv($message, $function, $table, $id, $value)
{
	$data = array(
		'function' => $function,
		'table' => $table,
		'id' => $id,
		'value' => $$value
	);

	g_lev($message, $function, $data);
}

//Log error
function g_ler($message, $function, $value = null)
{
	$message = "$message <$function>";
	files_write_log('ERROR', $message, $value);
}

//Log DB error
function g_ldr($message, $function, $table, $id, $value)
{
	$data = [
		'function' => $function,
		'table' => $table,
		'id' => $id,
		'value' => $value
	];

	g_ler($message, $function, $data);
}

//Log error from Exception variable
function g_ldx($event, $data)
{
	if (isset($data['table'])) g_ldr($event . ' [' . $data['details'] . ']', $data['function'], $data['table'], $data['id'], $data['value']);
	else g_ler($event, $data['function'], $data['value']);
}

//-----Other functions

//Redirect to 404
function g_404()
{
	global $wp_query;
	$wp_query->set_404();
	status_header(404);
	get_template_part(404);
	exit();
}

//Custom exception with array parameters
class DataException extends Exception
{
	private $funname;
	private $data;

	public function __construct($message, $data = null)
	{
		parent::__construct($message);
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}
}
