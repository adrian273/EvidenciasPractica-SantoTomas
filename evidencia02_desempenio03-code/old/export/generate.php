<?php
require_once 'gridExcelGenerator.php';
require_once 'gridExcelWrapper.php';

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

/*$xmlString = <<<EOF
<?xml version="1.0" encoding="ISO-8859-1"?><rows><head><column width="*" type="link">Invoice #</column><column width="*" type="ro">Invoice Date</column><column width="*" type="ro">Sent Date</column><column width="5" type="ro">Note</column><column width="*">Due date</column><column width="*">Bill Amount</column><column width="*">Payment</column><column width="*">Balance</column><column width="*">Agency Name</column><settings><colwidth>%</colwidth></settings></head><row id="8792"><cell>5342766521^javascript:detailInvoice(8792)</cell><cell>08/14/2018</cell><cell>08/14/2018</cell><cell><![CDATA[]]></cell><cell>09/13/2018</cell><cell>$  910.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;910.00&lt;/font&gt;</cell><cell>Careplus Health Services </cell></row><row id="8790"><cell>5342570961^javascript:detailInvoice(8790)</cell><cell>08/14/2018</cell><cell>08/14/2018</cell><cell><![CDATA[]]></cell><cell>09/13/2018</cell><cell>$  1,990.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,990.00&lt;/font&gt;</cell><cell>North Texas Home Health Care</cell></row><row id="8788"><cell>5341944341^javascript:detailInvoice(8788)</cell><cell>08/13/2018</cell><cell>08/13/2018</cell><cell><![CDATA[]]></cell><cell>09/12/2018</cell><cell>$  180.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;180.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8767"><cell>5338437491^javascript:detailInvoice(8767)</cell><cell>08/09/2018</cell><cell>08/09/2018</cell><cell><![CDATA[]]></cell><cell>09/08/2018</cell><cell>$  570.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;570.00&lt;/font&gt;</cell><cell>Alpha Home Health Services</cell></row><row id="8719"><cell>5334150401^javascript:detailInvoice(8719)</cell><cell>08/04/2018</cell><cell>08/04/2018</cell><cell><![CDATA[]]></cell><cell>09/03/2018</cell><cell>$  180.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;180.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8665"><cell>5329747171^javascript:detailInvoice(8665)</cell><cell>07/30/2018</cell><cell>07/30/2018</cell><cell><![CDATA[]]></cell><cell>08/29/2018</cell><cell>$  180.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;180.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8664"><cell>5329170971^javascript:detailInvoice(8664)</cell><cell>07/29/2018</cell><cell>07/29/2018</cell><cell><![CDATA[]]></cell><cell>08/28/2018</cell><cell>$  250.00</cell><cell>$  250.00</cell><cell>$  0.00</cell><cell>Apex Homecare, Inc.</cell></row><row id="8617"><cell>5325832521^javascript:detailInvoice(8617)</cell><cell>07/25/2018</cell><cell>07/25/2018</cell><cell><![CDATA[]]></cell><cell>08/24/2018</cell><cell>$  360.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;360.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8613"><cell>5322781611^javascript:detailInvoice(8613)</cell><cell>07/22/2018</cell><cell>07/24/2018</cell><cell><![CDATA[]]></cell><cell>08/21/2018</cell><cell>$  170.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;170.00&lt;/font&gt;</cell><cell>Ally Home Health</cell></row><row id="8571"><cell>5318978871^javascript:detailInvoice(8571)</cell><cell>07/18/2018</cell><cell>07/18/2018</cell><cell><![CDATA[]]></cell><cell>08/17/2018</cell><cell>$  810.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;810.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8570"><cell>5318977921^javascript:detailInvoice(8570)</cell><cell>07/18/2018</cell><cell>07/18/2018</cell><cell><![CDATA[]]></cell><cell>08/17/2018</cell><cell>$  1,880.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,880.00&lt;/font&gt;</cell><cell>Optimum Home Health Care</cell></row><row id="8569"><cell>5317722331^javascript:detailInvoice(8569)</cell><cell>07/16/2018</cell><cell>07/16/2018</cell><cell><![CDATA[]]></cell><cell>08/15/2018</cell><cell>$  1,800.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,800.00&lt;/font&gt;</cell><cell>North Texas Home Health Care</cell></row><row id="8568"><cell>5317238141^javascript:detailInvoice(8568)</cell><cell>07/15/2018</cell><cell>07/15/2018</cell><cell><![CDATA[]]></cell><cell>07/30/2018</cell><cell>$  630.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;630.00&lt;/font&gt;</cell><cell>Health Quest Home Health </cell></row><row id="8567"><cell>5317237901^javascript:detailInvoice(8567)</cell><cell>07/15/2018</cell><cell>07/15/2018</cell><cell><![CDATA[]]></cell><cell>07/30/2018</cell><cell>$  1,980.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,980.00&lt;/font&gt;</cell><cell>Health Quest Home Health </cell></row><row id="8566"><cell>5317189801^javascript:detailInvoice(8566)</cell><cell>07/15/2018</cell><cell>07/15/2018</cell><cell><![CDATA[]]></cell><cell>08/14/2018</cell><cell>$  570.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;570.00&lt;/font&gt;</cell><cell>Careplus Health Services </cell></row><row id="8563"><cell>5317009101^javascript:detailInvoice(8563)</cell><cell>07/15/2018</cell><cell>07/24/2018</cell><cell><![CDATA[]]></cell><cell>08/14/2018</cell><cell>$  160.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;160.00&lt;/font&gt;</cell><cell>Ally Home Health</cell></row><row id="8519"><cell>5312453781^javascript:detailInvoice(8519)</cell><cell>07/10/2018</cell><cell>07/10/2018</cell><cell><![CDATA[]]></cell><cell>08/09/2018</cell><cell>$  2,250.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;2,250.00&lt;/font&gt;</cell><cell>Committed Home Health Care</cell></row><row id="8472"><cell>5308937891^javascript:detailInvoice(8472)</cell><cell>07/06/2018</cell><cell>07/24/2018</cell><cell><![CDATA[]]></cell><cell>08/05/2018</cell><cell>$  330.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;330.00&lt;/font&gt;</cell><cell>Ally Home Health</cell></row><row id="8471"><cell>5308891581^javascript:detailInvoice(8471)</cell><cell>07/06/2018</cell><cell>07/06/2018</cell><cell><![CDATA[]]></cell><cell>08/05/2018</cell><cell>$  90.00</cell><cell>$  90.00</cell><cell>$  0.00</cell><cell>Apex Homecare, Inc.</cell></row><row id="8462"><cell>5308051871^javascript:detailInvoice(8462)</cell><cell>07/05/2018</cell><cell>07/05/2018</cell><cell><![CDATA[]]></cell><cell>08/04/2018</cell><cell>$  1,060.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,060.00&lt;/font&gt;</cell><cell>Shalem Home Health Care,Inc</cell></row><row id="8457"><cell>5305499721^javascript:detailInvoice(8457)</cell><cell>07/02/2018</cell><cell>07/02/2018</cell><cell><![CDATA[]]></cell><cell>08/01/2018</cell><cell>$  1,550.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,550.00&lt;/font&gt;</cell><cell>Careplus Health Services </cell></row><row id="8409"><cell>5302079021^javascript:detailInvoice(8409)</cell><cell>06/28/2018</cell><cell>06/28/2018</cell><cell><![CDATA[]]></cell><cell>07/28/2018</cell><cell>$  720.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;720.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8399"><cell>5299773001^javascript:detailInvoice(8399)</cell><cell>06/25/2018</cell><cell>06/25/2018</cell><cell><![CDATA[]]></cell><cell>07/25/2018</cell><cell>$  250.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;250.00&lt;/font&gt;</cell><cell>Alpha Home Health Services</cell></row><row id="8350"><cell>5295172431^javascript:detailInvoice(8350)</cell><cell>06/20/2018</cell><cell>06/20/2018</cell><cell><![CDATA[]]></cell><cell>07/20/2018</cell><cell>$  1,390.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,390.00&lt;/font&gt;</cell><cell>Optimum Home Health Care</cell></row><row id="8349"><cell>5295172221^javascript:detailInvoice(8349)</cell><cell>06/20/2018</cell><cell>06/20/2018</cell><cell><![CDATA[]]></cell><cell>07/20/2018</cell><cell>$  1,480.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;1,480.00&lt;/font&gt;</cell><cell>North Texas Home Health Care</cell></row><row id="8348"><cell>5295127191^javascript:detailInvoice(8348)</cell><cell>06/20/2018</cell><cell>06/20/2018</cell><cell><![CDATA[]]></cell><cell>07/20/2018</cell><cell>$  160.00</cell><cell>$  160.00</cell><cell>$  0.00</cell><cell>Apex Homecare, Inc.</cell></row><row id="8347"><cell>5294732141^javascript:detailInvoice(8347)</cell><cell>06/19/2018</cell><cell>06/19/2018</cell><cell><![CDATA[]]></cell><cell>07/19/2018</cell><cell>$  240.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;240.00&lt;/font&gt;</cell><cell>Alpha Home Health Services</cell></row><row id="8341"><cell>5294149571^javascript:detailInvoice(8341)</cell><cell>06/19/2018</cell><cell>07/24/2018</cell><cell><![CDATA[]]></cell><cell>07/18/2018</cell><cell>$  160.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;160.00&lt;/font&gt;</cell><cell>Ally Home Health</cell></row><row id="8337"><cell>5290227471^javascript:detailInvoice(8337)</cell><cell>06/14/2018</cell><cell>06/14/2018</cell><cell><![CDATA[]]></cell><cell>07/14/2018</cell><cell>$  250.00</cell><cell>$  250.00</cell><cell>$  0.00</cell><cell>Bright Home Health Care</cell></row><row id="8335"><cell>5289976841^javascript:detailInvoice(8335)</cell><cell>06/14/2018</cell><cell>06/14/2018</cell><cell><![CDATA[]]></cell><cell>07/14/2018</cell><cell>$  1,350.00</cell><cell>$  1,350.00</cell><cell>$  0.00</cell><cell>HNB Home Health Agency</cell></row><row id="8333"><cell>5288100371^javascript:detailInvoice(8333)</cell><cell>06/12/2018</cell><cell>07/24/2018</cell><cell><![CDATA[]]></cell><cell>06/12/2018</cell><cell>$  320.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;320.00&lt;/font&gt;</cell><cell>Ally Home Health</cell></row><row id="8330"><cell>5287240711^javascript:detailInvoice(8330)</cell><cell>06/11/2018</cell><cell>06/11/2018</cell><cell><![CDATA[]]></cell><cell>07/11/2018</cell><cell>$  90.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;90.00&lt;/font&gt;</cell><cell>Alpha Home Health Services</cell></row><row id="8259"><cell>5282097641^javascript:detailInvoice(8259)</cell><cell>06/05/2018</cell><cell>06/05/2018</cell><cell><![CDATA[]]></cell><cell>06/20/2018</cell><cell>$  270.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;270.00&lt;/font&gt;</cell><cell>Health Quest Home Health </cell></row><row id="8258"><cell>5282097471^javascript:detailInvoice(8258)</cell><cell>06/05/2018</cell><cell>06/05/2018</cell><cell><![CDATA[]]></cell><cell>06/20/2018</cell><cell>$  2,880.00</cell><cell>$  2,880.00</cell><cell>$  0.00</cell><cell>Health Quest Home Health </cell></row><row id="8257"><cell>5282096391^javascript:detailInvoice(8257)</cell><cell>06/05/2018</cell><cell>06/05/2018</cell><cell><![CDATA[]]></cell><cell>07/05/2018</cell><cell>$  2,430.00</cell><cell>$  2,430.00</cell><cell>$  0.00</cell><cell>North Texas Home Health Care</cell></row><row id="8255"><cell>^javascript:detailInvoice(8255)</cell><cell>06/04/2018</cell><cell></cell><cell><![CDATA[<a href="javascript://" onclick="(isIE()?event:arguments[0]).cancelBubble = true; Tip('Test', OFFSETY, 18);"><img src="https://www.homehealthsoft.com/webapp/style/images/icon_note.jpg" border="0" /></a>&nbsp;]]></cell><cell>06/04/2018</cell><cell></cell><cell>$  48.00</cell><cell>$  -48.00 (CR)</cell><cell>Test Agency</cell></row><row id="8242"><cell>5278757231^javascript:detailInvoice(8242)</cell><cell>06/01/2018</cell><cell>06/01/2018</cell><cell><![CDATA[]]></cell><cell>07/01/2018</cell><cell>$  1,490.00</cell><cell>$  1,490.00</cell><cell>$  0.00</cell><cell>Careplus Health Services </cell></row><row id="8241"><cell>5278756161^javascript:detailInvoice(8241)</cell><cell>06/01/2018</cell><cell>06/01/2018</cell><cell><![CDATA[]]></cell><cell>07/01/2018</cell><cell>$  900.00</cell><cell>$  900.00</cell><cell>$  0.00</cell><cell>HNB Home Health Agency</cell></row><row id="8239"><cell>5277863091^javascript:detailInvoice(8239)</cell><cell>05/31/2018</cell><cell>05/31/2018</cell><cell><![CDATA[]]></cell><cell>06/30/2018</cell><cell>$  630.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;630.00&lt;/font&gt;</cell><cell>HNB Home Health Agency</cell></row><row id="8238"><cell>5277768221^javascript:detailInvoice(8238)</cell><cell>05/31/2018</cell><cell>07/24/2018</cell><cell><![CDATA[]]></cell><cell>06/30/2018</cell><cell>$  90.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;90.00&lt;/font&gt;</cell><cell>Ally Home Health</cell></row><row id="8180"><cell>5271281291^javascript:detailInvoice(8180)</cell><cell>05/23/2018</cell><cell></cell><cell><![CDATA[]]></cell><cell>05/23/2018</cell><cell>$  85.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;85.00&lt;/font&gt;</cell><cell>Test Agency</cell></row><row id="8179"><cell>5271262911^javascript:detailInvoice(8179)</cell><cell>05/23/2018</cell><cell></cell><cell><![CDATA[]]></cell><cell>05/23/2018</cell><cell>$  340.00</cell><cell>$  0.00</cell><cell>$  &lt;font color=&quot;red&quot;&gt;340.00&lt;/font&gt;</cell><cell>Test Agency</cell></row><row id="8177"><cell>5269111101^javascript:detailInvoice(8177)</cell><cell>05/21/2018</cell><cell>05/24/2018</cell><cell><![CDATA[]]></cell><cell>06/20/2018</cell><cell>$  890.00</cell><cell>$  890.00</cell><cell>$  0.00</cell><cell>Apex Homecare, Inc.</cell></row></rows>
EOF;*/

if ($xmlString) {
	// remove CDATA and keep text inside this so that strip_tags function doesn't remove header text.
	$xmlString = preg_replace("/\<\!\[CDATA\[([a-zA-Z0-9 #<=\"'\(\)\?:\{\}\[\];\/\/.&-_]+)\]\]\>/", "$1", $xmlString);
	// remove link created by ^ character in xml
	$xmlString = preg_replace("/\^http[a-zA-Z0-9#=\"'\(\)\?:\{\}\[\];\/\/.&-_]+ /", " ", $xmlString);
	$xmlString = '<?xml version="1.0"?>'.strip_tags($xmlString, '<?xml><rows><head><columns><column><row><cell>');
	$xmlString = cleanit($xmlString);
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
$excel = new gridExcelGenerator();
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
	$string = preg_replace('~&#x([0-9a-f]+);~ei', '', $string);
    $string = preg_replace('~&#([0-9]+);~e', '', $string);
    $string = preg_replace('~&([a-zA-Z0-9]+);~e', '', $string);

    // if still have any & character remove it
    $string=str_replace("&","",$string);
    $string=str_replace("&","",$string);
    $string=str_replace(";","",$string);

    return $string;
}

?>