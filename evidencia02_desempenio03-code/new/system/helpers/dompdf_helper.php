<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $stream=true) 
{
    require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
	$dompdf->set_paper('portrait', 'A4');
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        //$dompdf->stream($filename.".pdf");
		$dompdf->stream($filename.".pdf");
    } else {
		$save_loc = $filename.".pdf";
        file_put_contents($save_loc, $dompdf->output( array("compress" => 0) ));
    }
}
?>