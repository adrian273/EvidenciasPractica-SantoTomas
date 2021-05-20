<?php
require_once 'GridExcelGenerator.php';
require_once 'GridExcelWrapper.php';

// error_reporting(E_ALL);
set_time_limit(0);

$debug = false;
$error_handler = set_error_handler("PDFErrorHandler");

$xmlString = "";
if (isset($_POST['grid_xml'])) {
	// if (get_magic_quotes_gpc()) {
	// 	$xmlString = stripslashes($_POST['grid_xml']);
	// } else {
	// 	$xmlString = $_POST['grid_xml'];
	// }
	// $xmlString = urldecode($xmlString);
	$xmlString = base64_decode($_POST['grid_xml']);
}
//echo $xmlString;
if ($xmlString) {
	// remove CDATA and keep text inside this so that strip_tags function doesn't remove header text.
	$xmlString = preg_replace("/\<\!\[CDATA\[([a-zA-Z0-9 #<=\"'\(\)\?:\{\}\[\];\/\/.&-_]+)\]\]\>/", "$1", $xmlString);
	// remove link created by ^ character in xml
	$xmlString = preg_replace("/\^http[a-zA-Z0-9#=\"'\(\)\?:\{\}\[\];\/\/.&-_]+ /", " ", $xmlString);
	$xmlString = '<?xml version="1.0"?>'.strip_tags($xmlString, '<?xml><rows><head><columns><column><row><cell>');
	$xmlString = cleanit($xmlString); // you have to check this **
}

if (!$xmlString) {
	// $filename = "debug/debug_2016_03_03__21_03_57.xml";
	$filename = "debug/debug_2016_03_04__14_30_41.xml";
	$handle = fopen($filename, "r");
	$xmlString = fread($handle, filesize($filename));
	// echo $xmlString;exit();
	fclose($handle);
	$debug = false;
}else if ($debug == true) {
	error_log($xmlString, 3, 'debug/debug_'.date("Y_m_d__H_i_s").'.xml');
}

$xml = simplexml_load_string($xmlString);
$excel = new GridExcelGenerator();
$excel->printGrid($xml);

function PDFErrorHandler ($errno, $errstr, $errfile, $errline) {
	global $xmlString, $debug;
	// if ($errno < 1024) {
		if ($debug == true) {
			// error_log($xmlString, 3, 'error_report_'.date("Y_m_d__H_i_s").'.xml');
			error_log($errstr.', error number'.$errno.', file:'.$errfile.' line number:'.$errline, 3, 'error/error_report_'.date("Y_m_d").'.xml');
			// exit($errstr.', error number'.$errno.', file:'.$errfile.' line number:'.$errline);
		}
	// }

}

function cleanit($string){
    // if have any ^javascript grid content rmeove it
    if (strpos($string, '^javascript') !== FALSE) {
		$string = preg_replace("/\^javascript:[^<]+/", "", $string);
	}
	

	// remove html entities and special characters
	//$string = preg_replace('~&#x([0-9a-f]+);~i', '', $string);
    //$string = preg_replace('~&#([0-9]+);~', '', $string);
    //$string = preg_replace('~&([a-zA-Z0-9]+)', '', $string);

    // if still have any & character remove it
    $string=str_replace("&","",$string);
    $string=str_replace("&","",$string);
    $string=str_replace(";","",$string);
	$string=str_replace("nbsp","",$string);
    return $string;
}

?>