<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

 class MY_Controller extends CI_Controller {

	function __construct() {
		
		parent::__construct();
		if (!$this->session->userdata('logged') && $this->uri->segment(1) != 'welcome' && $this->uri->segment(1) != '') 		{
			$config =& get_config();
			header("Location: " . $config["index_url"]);
		}
		$this->_chkSystem();

		// $this->tpl->assign('credentialAlertDays',$this->_countCredentialExpDays());
		//echo "<pre>"; print_r($this->statemdl->getAll()); echo "</pre>";
		
		$this->tpl->assign('state_list', $this->statemdl->getAll());
		$this->tpl->assign('time_zone_list', $this->parametermdl->getByType(66));
		$this->tpl->assign('sex_list', $this->parametermdl->getByType(2));
		$this->tpl->assign('patient_status_list', $this->parametermdl->getByType(13));
    
    	$this->tpl->assign('desktop_url', str_replace("/boot/","/webapp/",base_url()) );
		$this->tpl->assign('mobile_url', str_replace("/webapp/","/boot/",base_url()) );

		// load visit pdf helpers
		$this->load->helper('artvi_pdf');
		$this->load->helper('ptev_pdf');
		$this->load->helper('stev_pdf');
		$this->load->helper('otev_pdf');
		$this->load->helper('ptpn_pdf');
		$this->load->helper('otpn_pdf');
		$this->load->helper('stpn_pdf');
		$this->load->helper('form8606A_pdf');
		$this->load->helper('form3625_pdf');
		$this->load->helper('quartsumm_pdf');
		
	}
	
	function getAgencyId () {
		
		return $this->session->userdata('agency_id');
		
	}
	
	function getLob () {
		return $this->session->userdata('line_of_business');
	}
	
	function getLobText () {
		return $this->session->userdata('line_of_business_text');
	}
	
	function getAgencyType () {
		
		$agency = $this->agencymdl->getByAgencyId($this->getAgencyId());
		return isset($agency->agency_type) ? $agency->agency_type : '';
		
	}
	
	function getUserId () {
		
		return $this->session->userdata('user_id');
		
	}
	
	function getUsAgyId () {
		
		if ($this->getAgencyId() == '' || $this->getUserId() == '') return 0;
		
		$usagy = $this->usagymdl->getByUserAgencyId($this->getAgencyId(), $this->getUserId());
		return $usagy->us_agy_id;
		
	}
	
	function getUserProfileId () {
		
		$usagy = $this->usagymdl->getByUsAgyId($this->getUsAgyId());
		
		return isset($usagy->profile_id) ? $usagy->profile_id : 0;
		
	}
	
	function getUserDisciplineId () {
		
		$usagy = $this->usagymdl->getByUsAgyId($this->getUsAgyId());
		
		return $usagy->discipline_id;
		
	}
	
	function getUserDiscipline () {
		
		$usagy = $this->usagymdl->getByUsAgyId($this->getUsAgyId());
		
		$aux = array(
			2 => 'pt',
			3 => 'pt',
			4 => 'st',
			5 => 'st',
			6 => 'ot',
			7 => 'ot'
		);
		
		return @$aux[$usagy->discipline_id];
		
	}
	
	function isValidDate($date, $format = 'Y-m-d H:i')
	{
	    $version = explode('.', phpversion());
	    if (((int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17)) {
	        $d = DateTime::createFromFormat($format, $date);
	    } else {
	        $d = new DateTime(date($format, strtotime($date)));
	    }
	    return $d && $d->format($format) == $date;
	}

	function assignPostData ( &$objectmdl, $object=null ) {
		
		$array_ele = (is_null($object) ? $_POST : $object);
		
		foreach ($array_ele as $key => $val) {
			
			if ($val == '') $val = null;
			
			if (is_null($object) AND strpos($key, 'date') !== FALSE && $val != null) {
				$val = standard_date(human_to_unix($val), 'MYSQL');
			}
					
			if (array_key_exists($key, get_class_vars(get_class($objectmdl)))) {
				
				$objectmdl->$key = $val;
				
			}
			
		}
		
	}
	
	function assignObject ( $object, $debug=FALSE ) {
		
		foreach((array)$object as $key => $val) {

			$this->tpl->assign($key, $val);
			if ($debug) echo $key . " => " .  $val . "<br>";
			
		}
		
	}
	
	
	// app function
	function getPatients ( $keyword=null, $tab_013_status=null, $normal_result=true, $discipline=null, $and_where = array(), $order_by_str=null ) {
		
		$us_agy_id = $this->getUsAgyId();
		
		$us_agy 		   = $this->usagymdl->getByUsAgyId($us_agy_id);
		$us_agy_permission = $this->usagypermissionmdl->getByUsAgyId($us_agy_id, null, false);
		
		if ($this->getAgencyType() != 'C' && ($us_agy->profile_id == 1 || in_array(4, $us_agy_permission) == TRUE)) {
			if (is_null($discipline) OR !$discipline) {
				return $this->patientmdl->getByAgencyId($this->getAgencyId(), $keyword, $tab_013_status, $normal_result, $and_where, $order_by_str);
			} else {
				return $this->patientmdl->getActivatedByAgencyId($this->getAgencyId(), $discipline, $keyword, $tab_013_status, $normal_result, $and_where, $order_by_str);
			}
			
		} else {
			
			if (is_null($discipline) OR !$discipline) {
				return $this->patientmdl->getByUsAgyId($us_agy_id, $keyword, $tab_013_status, $normal_result, $and_where, $order_by_str);
			} else {
				return $this->patientmdl->getActivatedByUsAgyId($us_agy_id, $discipline, $keyword, $tab_013_status, $normal_result, $and_where, $order_by_str);
			}			
			
		}
		
	}
	
	function getActivePatients ( $keyword=null, $tab_013_status=2, $normal_result=true ) {
	
		$buffer = array();
		
		if ($this->getAgencyType() == 'C' && $this->getUserProfileId() == 3) {
			$pdata = $this->patientcontractormdl->getByContractorId($this->getAgencyId(), $keyword, $tab_013_status);
		} else {
			$pdata = $this->getPatients($keyword, $tab_013_status);
		}
	
		foreach ($pdata as $patient) {
			
			$last = $this->visitlogmdl->getLastByPatient($patient->patient_id);
			
			if ($last && (mysql_to_unix($last->visit_date_time_plus_seven) > mysql_to_unix(date('Y-m-d')))) {
				
				array_push($buffer, $patient);
				
			}
			
		}
		
		return $buffer;
		
	}
	
	function hasPatientAccess ( $patient_id ) {
		
		$us_agy_id = $this->getUsAgyId();
		
		$us_agy 		   = $this->usagymdl->getByUsAgyId($us_agy_id);
		$us_agy_permission = $this->usagypermissionmdl->getByUsAgyId($us_agy_id, null, false);
		
		if ($this->getAgencyType() != 'C' && ($us_agy->profile_id == 1 || in_array(4, $us_agy_permission)) == TRUE) {
			
			return TRUE;
			
		
		} else if ($this->getAgencyType() == 'C' && count($this->patientcontractormdl->get($patient_id, $this->getAgencyId()))) {
			
			return TRUE;
			
		} else {

			return array_key_exists($patient_id, $this->patientmdl->getByUsAgyId($us_agy_id, null, null, null, FALSE));
			
		}
		
	}
	
	function validatePermission ( $patient_id ) {
		
		$patient = $this->patientmdl->getById($patient_id);
		
	}

	function validateNewVisitTime($cms485_id, $starttime, $endtime=null, $default_visit_duration=45, $visit_log_id=null){		
		$starttime = date("Y-m-d H:i:s", strtotime($starttime));
		// $default_visit_duration = 45; // minutes
		if (is_null($endtime)) {
			$endtime = date("Y-m-d H:i:s", strtotime($starttime)+($default_visit_duration*60));			
		}else{
			$endtime = date("Y-m-d H:i:s", strtotime($endtime));
		}

		$not_visit_log_id = "";
		if (!is_null($visit_log_id)) {
			$not_visit_log_id .= "AND visit_log_id !=".$visit_log_id;
		}
	    $query = $this->db->query("SELECT *
						FROM (SELECT visit_log_id, visit_date_time AS visit_start_date_time, if(visited_time_out IS NOT NULL, CONCAT(DATE_FORMAT(visit_date_time, '%Y-%m-%d'),' ',visited_time_out) , visit_date_time + INTERVAL 45 MINUTE) AS visit_end_date_time
							FROM vis_visit_log
							WHERE billable_YN=1 AND cms485_id = '{$cms485_id}' AND visit_type_id NOT IN (200, 350, 400) ".$not_visit_log_id." ) AS tmp
						WHERE '{$starttime}' < tmp.visit_end_date_time AND '{$endtime}' > tmp.visit_start_date_time");
	
	    if( $query->num_rows() > 0){
	    	return FALSE;
	    }else{
	    	return TRUE;
	    }
	}

	function validateNewVisitTimeForUser($visit_user_id, $starttime, $endtime=null, $default_visit_duration=45, $visit_log_id=null){		
		$starttime = date("Y-m-d H:i:s", strtotime($starttime));
		// $default_visit_duration = 45; // minutes
		if (is_null($endtime)) {
			$endtime = date("Y-m-d H:i:s", strtotime($starttime)+($default_visit_duration*60));			
		}else{
			$endtime = date("Y-m-d H:i:s", strtotime($endtime));
		}

		$not_visit_log_id = "";
		if (!is_null($visit_log_id)) {
			$not_visit_log_id .= "AND visit_log_id !=".$visit_log_id;
		}

	    $query = $this->db->query("SELECT *
						FROM (SELECT visit_log_id, visit_date_time AS visit_start_date_time, if(visited_time_out IS NOT NULL, CONCAT(DATE_FORMAT(visit_date_time, '%Y-%m-%d'),' ',visited_time_out) , visit_date_time + INTERVAL 45 MINUTE) AS visit_end_date_time
							FROM vis_visit_log
							WHERE billable_YN=1 AND visit_user_id = '{$visit_user_id}' AND visit_type_id NOT IN (200, 350, 400) ".$not_visit_log_id." ) AS tmp
						WHERE '{$starttime}' < tmp.visit_end_date_time AND '{$endtime}' > tmp.visit_start_date_time");
	
	    if( $query->num_rows() > 0){
	    	return FALSE;
	    }else{
	    	return TRUE;
	    }
	}
	
	function getOverlappingConflictedVisitId($visit_user_id, $starttime, $endtime=null, $default_visit_duration=45, $visit_log_id=null){		
		$starttime = date("Y-m-d H:i:s", strtotime($starttime));
		// $default_visit_duration = 45; // minutes
		if (is_null($endtime)) {
			$endtime = date("Y-m-d H:i:s", strtotime($starttime)+($default_visit_duration*60));			
		}else{
			$endtime = date("Y-m-d H:i:s", strtotime($endtime));
		}

		$not_visit_log_id = "";
		if (!is_null($visit_log_id)) {
			$not_visit_log_id .= "AND visit_log_id !=".$visit_log_id;
		}

	    $query = $this->db->query("SELECT *
						FROM (SELECT visit_log_id, visit_date_time AS visit_start_date_time, if(visited_time_out IS NOT NULL, CONCAT(DATE_FORMAT(visit_date_time, '%Y-%m-%d'),' ',visited_time_out) , visit_date_time + INTERVAL 45 MINUTE) AS visit_end_date_time
							FROM vis_visit_log
							WHERE visit_user_id = '{$visit_user_id}' AND visit_type_id NOT IN (200, 350, 400) ".$not_visit_log_id." ) AS tmp
						WHERE '{$starttime}' < tmp.visit_end_date_time AND '{$endtime}' > tmp.visit_start_date_time");
	
	    if( $query->num_rows() > 0){
	    	return $query->row()->visit_log_id;
	    }else{
	    	return null;
	    }
	}

	function validateUpdateVisitTime($visit_log_id, $starttime, $endtime=null, $cms485_id=null, $default_visit_duration=45){		
		if (is_null($cms485_id)) {
			$this->db->select("cms485_id");		
			$this->db->where("visit_log_id", $visit_log_id);		
			$query = $this->db->get('vis_visit_log');
			$cms485_id = @$query->row()->cms485_id;	
		}
		
		if (!$cms485_id) {
			return FALSE;
		}

		$starttime = date("Y-m-d H:i:s", strtotime($starttime));		
		// $default_visit_duration = 45; // minutes		
		if (is_null($endtime)) {
			$endtime = date("Y-m-d H:i:s", strtotime($starttime)+($default_visit_duration*60));			
		}else{
			$endtime = date("Y-m-d H:i:s", strtotime($endtime));
		}
	    $query = $this->db->query("SELECT *
						FROM (SELECT visit_log_id, visit_date_time AS visit_start_date_time, if(visited_time_out IS NOT NULL, CONCAT(DATE_FORMAT(visit_date_time, '%Y-%m-%d'),' ',visited_time_out) , visit_date_time + INTERVAL 45 MINUTE) AS visit_end_date_time
							FROM vis_visit_log
							WHERE visit_log_id != '{$visit_log_id}' AND cms485_id = '{$cms485_id}' AND visit_type_id NOT IN (200, 350, 400) ) AS tmp
						WHERE '{$starttime}' < tmp.visit_end_date_time AND '{$endtime}' > tmp.visit_start_date_time");
	
	    if( $query->num_rows() > 0){
	    	return FALSE;
	    }else{
	    	return TRUE;
	    }
	}
	function canApproveVisit ( $visit_log_id ) {
		
		$visit_log = $this->visitlogmdl->getById($visit_log_id);
		
		if (($visit_log->user_approver == $this->getUsAgyId() || $this->hasPermission(18)) && 
			(($visit_log->visit_status_id == 3 && $this->getAgencyType() == 'A') || 
			 ($visit_log->visit_status_id == 8 && $this->getAgencyType() == 'C'))) {
			return true;
		} else {
			return false;
		}
		
	}
	
	
	function hasPermission ( $permission_id ) {
		
		$us_agy_id = $this->getUsAgyId();
		
//		if ($us_agy_id == 0 || $this->getAgencyType() == 'C') return FALSE;
		if ($us_agy_id == 0) return FALSE;
		
		$us_agy 		   = $this->usagymdl->getByUsAgyId($us_agy_id);
		$us_agy_permission = $this->usagypermissionmdl->getByUsAgyId($us_agy_id, null, false);
		
		if ($us_agy->profile_id == 1) return TRUE;
		
		return in_array($permission_id, $us_agy_permission);
		
	}

	function hasPermissionHeader ( $permission_header_id, $us_agy_id=null ) {
		if (is_null($us_agy_id)) {
			$us_agy_id = $this->getUsAgyId();
		}
		
		if ($us_agy_id == 0) return FALSE;
		
		$us_agy = $this->usagymdl->getByUsAgyId($us_agy_id);
		if ($us_agy->profile_id == 1) return TRUE;

		$permission_headers = $this->usagypermissionmdl->getPermissionHeadersByUsAgyId($us_agy_id, false);
		
		
		return in_array($permission_header_id, $permission_headers);
		
	}

	function verify_epass ( $user_epass) {
		
		if ($this->usermdl->checkOldEPassword($this->getUserId(), $user_epass)) {
			return TRUE;
		} else {
			$this->validation->set_message('verify_epass', "The electronic pasword is not correct.");
			return FALSE;
		}	
	
	}
    
    function _fillValidation($mdl,$method){
    	
    	$data=$this->$mdl->$method();
		foreach($data as $key=>$value):
			$this->form_validation->_field_data[$key]['postdata']=$value;
		endforeach;
		
    }
    
    function _chkSystem(){
		
		$user = $this->usermdl->getById($this->session->userdata('user_id'));
		if(!$this->session->userdata('test_mode'))
		{

			/* if user is not admin (id=0) and is not a welcome page then set maintenace mode ON */
			if($this->getUserId()!=0 and $this->uri->segment(1) != 'welcome')
			{
				$sys_setting=$this->syssettingsmdl->getSysSetting('maintenance mode');
				if($sys_setting->sys_value)
				{
					/* if current section is an iframe then redirect via javascript, if not then redirect via PHP	 */
					if($this->uri->segment(1) != '')
					{
						echo "<script>window.parent.location.href='".base_url()."index.php/administrator/maintenance_mode';</script>";
					}
					else
					{
						redirect('administrator/maintenance_mode');
					}
				}				
				
			}    	
		}
		
    }
    
    function _countCredentialExpDays(){
		
		$count = $this->usagycredentialmdl->countAlertDays($this->getUsAgyId());
		if((array) $count){
			return "<span class='alert'><a target='content' href='".base_url()."index.php/useragency/alerts/index/".$this->getUsAgyId()."'>You have {$count} alert/s</span></a>";    	
		}
		
    }

	function calculatePayrollVisitAmount($visit_log_id, &$rate_picked_from, &$units, $update_rate=false){
		$visitlog = $this->visitlogmdl->getById($visit_log_id);
		$visit_type = $this->visittypemdl->getById($visitlog->visit_type_id);
		$patient = $this->patientmdl->getById($visitlog->patient_id);
		$payroll_visit_rate = false;

		$ppr_id = $this->teammdl->getPPRId($visitlog->patient_id,$visitlog->visit_user_id);
		if ($ppr_id) {
			$this->load->model('payrollratebyuserpatientvisittypemdl');
			$payroll_visit_rate = $this->payrollratebyuserpatientvisittypemdl->getRate($ppr_id,  $visitlog->visit_type_id, $visitlog->visited_date);
			$rate_picked_from = 'P'; // Patient Level rate
		}

		
		if ($payroll_visit_rate === FALSE) {
			$rate_picked_from = '';
			$this->load->model('payrollratebyuservisittypemdl');
			$payroll_visit_rate = $this->payrollratebyuservisittypemdl->getRate($visitlog->visit_user_id,  $visitlog->visit_type_id, $visitlog->visited_date);
			
			$rate_picked_from = 'U'; // User level rate			
		}
							
		if ($payroll_visit_rate === FALSE) {
			$rate_picked_from = '';
			$this->load->model('payrollratebydisciplinemdl');
			$payroll_visit_rate = $this->payrollratebydisciplinemdl->getRate($this->getAgencyId(),  $visitlog->visit_type_id, $visitlog->visited_date);	

			// $rate_picked_from = 'D';	// Default rate		
		}

		if ($payroll_visit_rate === FALSE) {
			$rate_picked_from = '';
		}
			
		
		if (in_array($visit_type->visit_program, array('form8606A','quartsumm','form3625','dorder'))) 
		{
			$payroll_visit_rate = 0.00;
		}else if ($payroll_visit_rate === FALSE) {
			if ($visit_type->visit_program == "msvt") {
				$payroll_visit_rate = 0.00;
			}
		}

		$payroll_visit_amount = $payroll_visit_rate;
		
		if ($payroll_visit_rate !==FALSE) {

			// Calculate payroll visit amount for special therapy
			if (in_array($visitlog->visit_type_id, array(101, 103, 104, 113, 114, 115, 116, 117, 118))) {
				$date2 = strtotime(date("Y-m-d")." ".$visitlog->visited_time_out);
				$date1 = strtotime(date("Y-m-d")." ".$visitlog->visited_time_in);
				$visit_duration = floor(($date2 - $date1)/60);
				if ($visit_duration < 0) {
					$date2 = strtotime(date("Y-m-d ", mktime(0,0,0,date("m"),date("j")+1,date("Y") )).$visitlog->visited_time_out);
            		$visit_duration = floor(($date2 - $date1)/60);
				}

				$this->load->model('spvisitunitmdl');
				$units = $this->spvisitunitmdl->get('PAYROLL', $visit_duration);

				if ($units > 0){
					$payroll_visit_amount = round($payroll_visit_rate*$units, 2);  
					$payroll_visit_amount = number_format($payroll_visit_amount, 2);   
					
					if (substr($payroll_visit_amount, -3) == '.99') {
		            	$payroll_visit_amount += 0.01;
		          	} else if (substr($payroll_visit_amount, -3) == '.98') {
		            	$payroll_visit_amount += 0.02;
		          	}       
				} else {
					$payroll_visit_amount = 0;
				}
			}
			

			if ($update_rate) {
				$us_agy = $this->usagymdl->getByUsAgyId($visitlog->visit_user_id);
				
				if (in_array($visit_type->visit_program, array('form8606A', 'quartsumm', 'form3625','dorder'))) {
					$us_agy->payroll_mileage_rate = 0;
					$this->visitlogmdl->updateFlex($visit_log_id, 'billable_YN', 0);
				}

				$this->visitlogmdl->updateRates($visit_log_id, $payroll_visit_amount, $us_agy->payroll_mileage_rate);
			}
		}
			
		return $payroll_visit_amount;
	}	
			
	function xml_entities($text, $charset = 'Windows-1252'){
	    //return $text;    
	    // Debug and Test
	    // $text = "test &amp; &trade; &amp;trade; abc &reg; &amp;reg; &#45;";
	    
	    // First we encode html characters that are also invalid in xml
	    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
	    $text = htmlentities($text, ENT_QUOTES);
	    $text=str_replace("'","",$text);
	    $text=str_replace('"',"",$text);
	    
	    return $text;
	}

 }
