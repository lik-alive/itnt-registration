<?php
//-----Fix non-breaking spaces
// dompdf/src/FrameReflower/Text.php
// $words = preg_split('/([ \t\n\r-]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

//Plugin folder
$DOMPDF_FOLDER = get_template_directory() . '/scripts/plugins/pdf/dompdf_0-8-3m';

//Load font script (need to be download additionally)
$LOAD_FONT_S = $DOMPDF_FOLDER . '/load_font.php';

//Enable Dompdf
require_once $DOMPDF_FOLDER . '/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//Add custom font (called once in the base thread)
function pdf_addFont($font_name, $font_path)
{
	global $LOAD_FONT_S;
	$_SERVER["argc"] = 3;
	$_SERVER["argv"][0] = $LOAD_FONT_S;
	$_SERVER["argv"][1] = $font_name;
	$_SERVER["argv"][2] = $font_path;
	include $LOAD_FONT_S;
}

//Create PDF representation of HTML
function pdf_create($html)
{
	//Process html
	$html = preg_replace('/class=["\']TestBody["\']/s', "", $html);
	$html = preg_replace('/<script>(.*?)<\/script>/s', '', $html);
	$html = preg_replace('/src=[\'\"]\.\..*?[\'\"]/', '', $html);
	$html = str_replace('%HEADERPIC%', get_template_directory() . '/emails/header_2020.png', $html);
	$html = str_replace('%SIGNPIC%', get_template_directory() . '/emails/signk.png', $html);

	//Convert before making replaces in text
	//$html = mb_convert_encoding($html, 'UTF-8', 'Windows-1251');

	//Options
	$options = new Options();
	$options->set('defaultFont', 'tnr'); //Enable russian letters
	$options->set('defaultPaperSize', 'A4');
	$options->set('pdfBackend', 'CPDF'); //Select engine
	$options->set('fontHeightRatio', 0.87); //Enable 1:1 line-height ratio

	//Dompdf converter
	$dompdf = new Dompdf($options);
	$dompdf->loadHtml($html);
	$dompdf->render();

	return $dompdf;
}

//Convert HTML to PDF and download
function pdf_stream($html)
{
	$dompdf = pdf_create($html);
	$dompdf->stream();
}

//Convert HTML to PDF and save to file
function pdf_save($html, $filename)
{
	$dompdf = pdf_create($html);
	$pdf = $dompdf->output();
	$res = file_put_contents($filename, $pdf);

	return $res;
}
