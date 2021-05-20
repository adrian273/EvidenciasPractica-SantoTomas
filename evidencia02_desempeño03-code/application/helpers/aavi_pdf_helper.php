<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function aavi_pdf ( $visit_log_id, $output='F' ) {
    $CI =& get_instance();

    $CI->load->model('aavimdl');

    $visit_log = $visitlog = $CI->visitlogmdl->getById($visit_log_id);
    $agy_user_id = $visit_log->user_id;
    $us1_user = $CI->usermdl->getById($agy_user_id);


    // printing pdf
    $aavi = $CI->aavimdl->get($visit_log_id);
    if (empty($aavi)) {
		$aavi = $CI->aavimdl->getEmpty();
		$aavi->visit_log_id = $visit_log_id;
	}
	// if($visitlog->signature != "") {
	// 	$aavi->pt_signature = $visitlog->signature;
	// }

    $patient    	= $CI->patientmdl->getById($visit_log->patient_id);
    // $insurance      = $CI->patientinsurancemdl->getPrimaryInsurance($visit_log->patient_id);
    // $assigned_to    = $CI->usagymdl->getByUsAgyId($visit_log->visit_user_id);
    // $approver    	= $CI->usagymdl->getByUsAgyId($visit_log->user_approver);
    $agency	    	= $CI->agencymdl->getByAgencyId($patient->agency_id);
    // $episde_icd		= $CI->episodeicdmdl->get($visit_log->cms485_id);
    // $physician    = $CI->agencymdl->getByAgencyId($visit_log->doctor_office_id);
	//$phys_user_id  	= $CI->usagymdl->getByUsAgyId($visit_log->phys_user_id);


    //$title_text = $visit_log->visit_description." Visit Date: " . standard_date(mysql_to_unix($visit_log->visit_date_time));
    $title_text = "AAVI Visit Date " . standard_date(mysql_to_unix($visit_log->visit_date_time));
    $filename_only = "Patient ".cut_firstchar_uc($patient->first_name)." ".$patient->last_name." ".$title_text;

    $filename = FCPATH;
    $filename = str_replace("\\","/", $filename);

    $data = (object) array_merge( (array)$visitlog, (array)$aavi);
    // echo "<pre>";print_r($data); exit();
    $data->patient = $patient;
    $data->agency = $agency;

    //print_r($data);exit;
    $data->base_url = base_url();
  	$content = $CI->load->view('task/documents/aavi_pdf.php',$data,true);

    // echo $content;exit;
    
    // convert in PDF
    require(APPPATH.'third_party/html2pdf_v4.03/html2pdf.class.php');    
    try
    {
        $html2pdf = new HTML2PDF('P','A3','en', true, 'UTF-8', array(15, 5, 15, 5)); // array(mL, mT, mR, mB)
        //$html2pdf = new HTML2PDF('P', 'A4', 'en');
        // $html2pdf->setModeDebug();
        //$html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        
        if ($output == 'D') {
        	$filename = $filename_only.'.pdf';

            $html2pdf->Output($filename, 'D');    
        } else if ($output == 'F') {
          	$c = 1;
            while(file_exists($filename.'tmp/'.$filename_only.'.pdf'))
            {
                $filename_only =  "Patient ".cut_firstchar_uc($patient->first_name)." ".$patient->last_name." ".$title_text." _".$c;
                $c++;
            }
            $filename = $filename.'tmp/'.$filename_only.'.pdf';

            $html2pdf->Output($filename, 'F');        
            return $filename;
        }
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    } 
}

