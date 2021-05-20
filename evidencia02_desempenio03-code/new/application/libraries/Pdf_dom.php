<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/dompdf-0.8.0/autoload.inc.php";

use Dompdf\Dompdf;

class Pdf_dom {

  public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait") {
      $dompdf = new DOMPDF();
      $dompdf->set_option("isPhpEnabled", true);
      $dompdf->loadHtml($html);
      $dompdf->setPaper($paper, $orientation);
      $dompdf->render();
      if ($stream) {
          $dompdf->stream($filename.".pdf", array("Attachment" => 1));
          exit(0);
      }
      else {
          return $dompdf->output();
      }
  }
}
?>