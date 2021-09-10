<?php
$FILESFOLDER = ABSPATH . 'files/';
$FILESURL = 'files/';
$TEMPDIR = 'temp/';
$LOGSDIR = $TEMPDIR . '/logs';

// Init file structure
function files_init()
{
	global $TEMPDIR;
	files_make_directory_TH($TEMPDIR);
	global $LOGSDIR;
	files_make_directory_TH($LOGSDIR);
}
files_init();

//-----General file functions

//Secure file name
function secureFileNaming($filename)
{
	$result = mb_ereg_replace('[ ]', '_', $filename);
	$result = mb_ereg_replace('[^а-яА-ЯёЁa-zA-Z0-9\-_.]', '', $result);
	return $result;
}

//Get filesystem path
function files_get_absolute_path($relpath)
{
	global $FILESFOLDER;
	if (false === mb_strpos($relpath, '.'))
		if ($relpath[mb_strlen($relpath) - 1] !== '/') $relpath .= '/';
	return $FILESFOLDER . $relpath;
}

//Get relative path
function files_get_relative_path($abspath)
{
	global $FILESFOLDER;
	if (mb_strpos($abspath, $FILESFOLDER) === 0)
		$relpath = mb_substr($abspath, mb_strlen($FILESFOLDER));
	else $relpath = $abspath;

	return $relpath;
}

//Get url path
function files_get_url_path($subpath)
{
	global $FILESURL;
	// return get_site_url(null, $FILESURL . $subpath);
	return $FILESURL . $subpath;
}

//Check is file exists
function files_is_exists($filename)
{
	if (is_null($filename) || g_ies($filename)) return false;

	global $FILESFOLDER;
	return file_exists($FILESFOLDER . $filename);
}

//Get file info
function getFile($filename)
{
	if (files_is_exists($filename)) {
		return array(
			'name' => basename($filename),
			'size' => filesize(files_get_absolute_path($filename)),
			'path' => $filename,
			'url' => files_get_url_path($filename)
		);
	} else return null;
}

//Get all files' info
function getFiles($subfolder)
{
	$folder = files_get_absolute_path($subfolder);

	$result = array();
	$files = glob($folder . '*');
	foreach ($files as $file) {
		if (is_file($file))
			$result[] = getFile(files_get_relative_path($file));
	}
	return $result;
}

//Make directory
function files_make_directory_TH($subfolder)
{
	global $FILESFOLDER;
	if (!file_exists($FILESFOLDER . $subfolder)) {
		$res = mkdir($FILESFOLDER . $subfolder, 0777, true);
		if (!$res) throw new DataException("Ошибка создания папки", array('function' => __FUNCTION__, 'value' => $subfolder));
	}
}

//Write text to file (in FILE directory)
function files_write_text_TH($text, $filename, $encoding = 'UTF-8')
{
	global $FILESFOLDER;
	$result = file_put_contents(
		$FILESFOLDER . $filename,
		mb_convert_encoding($text, $encoding)
	);

	if (false === $result) throw new DataException("Ошибка создания файла", array('function' => __FUNCTION__, 'value' => $filename));
}

// Read text from file (in FILE directory)
function files_read_text_TH($filename, $encoding = 'UTF-8')
{
	global $FILESFOLDER;
	$result = file_get_contents($FILESFOLDER . $filename);

	if (false === $result) throw new DataException("Ошибка чтения файла", array('function' => __FUNCTION__, 'value' => $filename));

	return mb_convert_encoding($result, $encoding);
}

//Copy file (Absolute path!)
function copyFile_TH($frompath, $topath)
{
	$fr = fopen($frompath, "r");
	if (!$fr) throw new DataException("Ошибка открытия файла", array('function' => __FUNCTION__, 'value' => files_get_relative_path($frompath)));
	$contents = fread($fr, filesize($frompath));
	fclose($fr);

	$fw = fopen($topath, "w");
	if (!$fw) throw new DataException("Ошибка создания файла", array('function' => __FUNCTION__, 'value' => files_get_relative_path($topath)));
	fwrite($fw, $contents);
	fclose($fw);
}

//Rearrange file array
function files_rearrange($files)
{
	$result = array();
	foreach ($files as $key1 => $value1)
		foreach ($value1 as $key2 => $value2)
			$result[$key2][$key1] = $value2;
	return $result;
}

//Copy uploaded files
function files_copy_uploaded_files_TH($uploaded, $subfolder)
{
	$folder = files_get_absolute_path($subfolder);
	$partCount = sizeof($uploaded['name']);

	files_make_directory_TH($subfolder);

	$files = array();
	for ($partNo = 0; $partNo < $partCount; $partNo++) {
		$filename = secureFileNaming($uploaded['name'][$partNo]);
		copyFile_TH($uploaded['tmp_name'][$partNo], $folder . $filename);
		$files[] = getFile($subfolder . $filename);

		g_lev("Файл сохранён", __FUNCTION__, $subfolder . $filename);
	}

	return $files;
}

//Copy uploaded files to temp directory
function files_copy_uploaded_files_to_temp_TH($uploaded)
{
	global $TEMPDIR;
	return files_copy_uploaded_files_TH($uploaded, $TEMPDIR);
}

//Copy uploaded file
function files_copy_uploaded_file_TH($uploaded, $subfolder)
{
	$folder = files_get_absolute_path($subfolder);
	files_make_directory_TH($subfolder);
	copyFile_TH($uploaded['tmp_name'], $folder . secureFileNaming($uploaded['name']));

	g_lev("Файл сохранён", __FUNCTION__, $subfolder . $uploaded['name']);
}

//Zip files
function files_zip_files_TH($files, $zipname)
{
	global $TEMPDIR;
	$folder = files_get_absolute_path($TEMPDIR);

	files_make_directory_TH($TEMPDIR);

	$zip = new ZipArchive();
	$res = $zip->open($folder . $zipname . '.zip', ZipArchive::CREATE);
	if ($res !== true) throw new DataException("Ошибка создания архива", array('function' => __FUNCTION__, 'value' => $TEMPDIR . $zipname . '.zip'));

	foreach ($files as $file) {
		$res = $zip->addFile(files_get_absolute_path($file['path']), secureFileNaming($file['name']));
		if (!$res) throw new DataException("Ошибка добавления файла в архив", array('function' => __FUNCTION__, 'value' => $TEMPDIR . $zipname . '.zip: ' . secureFileNaming($file['name'])));
	}

	$zip->close();

	return $TEMPDIR . $zipname . '.zip';
}

//Zip uploaded files
function files_zip_uploaded_files_TH($uploaded, $subfolder, $zipname)
{
	$folder = files_get_absolute_path($subfolder);
	$partCount = sizeof($uploaded['name']);

	files_make_directory_TH($subfolder);

	//Check if the file is already an archive
	$archives = array('rar', 'zip');
	$extension = mb_strtolower(pathinfo($uploaded['name'][0], PATHINFO_EXTENSION));
	if ($partCount === 1 && in_array($extension, $archives)) {
		copyFile_TH($uploaded['tmp_name'][0], $folder . str_replace('zip', $extension, $zipname));
		return;
	}

	$zip = new ZipArchive();
	$res = $zip->open($folder . $zipname, ZipArchive::CREATE);
	if ($res !== true) throw new DataException("Ошибка создания архива", array('function' => __FUNCTION__, 'value' => $subfolder . $zipname . '.zip'));

	for ($partNo = 0; $partNo < $partCount; $partNo++) {
		$res = $zip->addFile($uploaded['tmp_name'][$partNo], secureFileNaming($uploaded['name'][$partNo]));
		if (!$res) throw new DataException("Ошибка добавления файла в архив", array('function' => __FUNCTION__, 'value' => $subfolder . $zipname . '.zip: ' . secureFileNaming($uploaded['name'][$partNo])));
	}
	$zip->close();

	return $subfolder . $zipname . '.zip';
}
/*
//Move all files to inner tmp dir
function archiveFiles_TH($subfolder, $mask = '*')
{
	$count = 0;
	$folder = files_get_absolute_path($subfolder);
	$files = glob($folder . $mask);
	$tmp = 'arch ' . date('Y-m-d_H-i-s') . '/';
	foreach ($files as $file) {
		if (is_file($file)) {
			files_make_directory_TH($subfolder . $tmp);
			$res = rename($file, $folder . $tmp . basename($file));
			if (!$res) throw new DataException("Ошибка удаления файла", array('function' => __FUNCTION__, 'value' => files_get_relative_path($file)));
			$count++;
		}
	}

	if ($count > 0) g_lev("Файлы заархивированы", __FUNCTION__, $subfolder . $tmp . $mask);
	return $count;
}

//Move all files to inner tmp dir
function archiveFolder_TH($subfolder)
{
	$folder = files_get_absolute_path($subfolder);
	$tmp = 'arch_';
	$res = rename($folder, dirname($folder) . '/' . $tmp . basename($folder));

	g_lev("Файлы перемещены", __FUNCTION__, $subfolder . '/' . $tmp . basename($folder));
}

//Delete file
function deleteFile_TH($subfolder, $filename)
{
	$folder = files_get_absolute_path($subfolder);
	$file = $folder . $filename;
	if (is_file($file) && file_exists($file)) {
		$res = unlink($file);
		if (!$res) throw new DataException("Ошибка удаления файла", array('function' => __FUNCTION__, 'value' => $subfolder . $filename));
	}
}

//Delete all files in the folder
function clearFolder_TH($subfolder, $mask = '*')
{
	$folder = files_get_absolute_path($subfolder);
	$files = glob($folder . $mask);
	foreach ($files as $file) {
		if (is_file($file)) {
			$res = unlink($file);
			if (!$res) throw new DataException("Ошибка удаления файла", array('function' => __FUNCTION__, 'value' => files_get_relative_path($file)));
		}
	}
}

//Delete directory
function deleteDirectory_TH($subfolder)
{
	$folder = files_get_absolute_path($subfolder);
	if (file_exists($folder)) innerDeleteAll_TH($folder);
}

//Delete directory inner (Absolute path!)
function innerDeleteAll_TH($folder)
{
	global $GARR;
	$files = glob($folder . '*');
	foreach ($files as $file) {
		if (is_file($file)) {
			$res = unlink($file);
			if (!$res) throw new DataException("Ошибка удаления файла", array('function' => __FUNCTION__, 'value' => files_get_relative_path($file)));
		} else innerDeleteAll_TH($file . '/');
	}
	$res = rmdir($folder);
	if (!$res) throw new DataException("Ошибка удаления папки", array('function' => __FUNCTION__, 'value' => files_get_relative_path($folder)));
}*/

//----- PDF Functions
/**
 * Fill in PDF
 */
function files_fill_pdf_TH($pdfhtml, $replaces, $name)
{
	$html = @file_get_contents($pdfhtml);

	foreach ($replaces as $key => $value) {
		$html = str_replace($key, $value, $html);
	}

	$filename = 'temp/' . $name . '.pdf';
	$res = pdf_save($html, files_get_absolute_path($filename));

	if ($res === false) throw new DataException("Ошибка создания PDF-файла", array('function' => __FUNCTION__, 'value' => $replaces));

	return getFile($filename);
}

/***** LOGS *****/

function files_write_log($prefix, $message, $value = null)
{
	// Object output
	$log = [
		'DateTime' => date('Y-m-d H:i:s'),
		'ID_User' => is_user_logged_in() ? wp_get_current_user()->ID : null,
		'Prefix' => $prefix,
		'Message' => $message,
		'Value' => $value
	];

	// Write to file
	global $LOGSDIR;
	$apath = files_get_absolute_path($LOGSDIR . '/log_' . date('Y-m-d') . '.txt');
	file_put_contents(
		$apath,
		"\n" . json_encode($log, JSON_UNESCAPED_UNICODE) . "\n",
		FILE_APPEND
	);
	chmod($apath, fileperms($apath) | 16);
}
