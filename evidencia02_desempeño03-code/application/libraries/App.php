<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class App {
		
		var $eval_config_number = array(
									1 => array(
											array(14),
											array(19)
									),
									2 => array(
											array(12, 13),
											array(18, 19)
									),
									3 => array(
											array(11, 12, 13),
											array(17, 18, 19)
									)
								);
								
		var $eval_config_number_eval = array(
									1 => array(
											array(14),
											array(20)
									),
									2 => array(
											array(12, 13),
											array(18, 19)
									),
									3 => array(
											array(11, 12, 13),
											array(17, 18, 19)
									)
								);
								
		var $eval_config_number_plain = array(
									1 => array(13, 19),
									2 => array(12, 13, 18, 19),
									3 => array(11, 12, 13, 17, 18, 19)
								);
								
		var $visit_to_count = array(10, 12, 40, 42, 60, 62);
		
		function __construct () {
			
			$this->CI =& get_instance();
			
		}
		
		function getVisit ( $cms_485, $number=array() ) {

			return $this->CI->visitlogmdl->getByEpisodeAndNumber($cms_485, $number);

		}
		
		function updateEpisodeVisitNumber ( $cms485_id ) {
			
			$visit_logs = $this->CI->visitlogmdl->getByEpsodeId($cms485_id, $this->visit_to_count,  null, null, null, null, null, null, 1);
			
			$this->CI->visitlogmdl->updateFlex($cms485_id, 'visit_log_number', 0, 'cms485_id');
			foreach ($visit_logs as $n => $visit) {
				
				$this->CI->visitlogmdl->updateFlex($visit->visit_log_id, 'visit_log_number', ($n + 1));
				
			}
			
			
		}
		
		function validateCalendar ( $cms485_id ) {
			// Remove 13/19th visit validation
			return true;
			
			/*$episodeDisciplines = $this->CI->visitlogmdl->getDisciplineQuantity($cms485_id);
			
			$episodeDisciplinesQuantity = count($episodeDisciplines);
			
			switch ($episodeDisciplinesQuantity) {
				case 1:
					return $this->validateOneDiscipline($cms485_id, $episodeDisciplines);
					break;
				case 2:
					return $this->validateTwoDiscipline($cms485_id, $episodeDisciplines);
					break;
				case 3:
					return $this->validateThreeDiscipline($cms485_id, $episodeDisciplines);
					break;
				default:
					return true;
					break;
			}*/
			
		}

		function validateCalendarArchive ( $cms485_id ) {
			// remove 13/19th validation
			return true;
			/*
			$episodeDisciplines = $this->CI->visitlogmdl->getDisciplineQuantityArchive($cms485_id);
			
			$episodeDisciplinesQuantity = count($episodeDisciplines);
			
			switch ($episodeDisciplinesQuantity) {
				case 1:
					return $this->validateOneDisciplineArchive($cms485_id, $episodeDisciplines);
					break;
				case 2:
					return $this->validateTwoDisciplineArchive($cms485_id, $episodeDisciplines);
					break;
				case 3:
					return $this->validateThreeDisciplineArchive($cms485_id, $episodeDisciplines);
					break;
				default:
					return true;
					break;
			}*/
			
		}
		
		function validateOneDiscipline ( $cms485_id, $episodeDisciplines ) {
			
			$valid = true;
			
			if ($episodeDisciplines[0]->quantity > 13) {
				
				$visit_13 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(13));
				if (@$visit_13[0]->eval != 'Y') {
					$valid = false;
				}
				
			}
			
			if ($episodeDisciplines[0]->quantity > 19) {
				
				$visit_19 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(19));
				if (@$visit_19[0]->eval != 'Y') {
					$valid = false;
				}
				
			}
			
			return $valid;
			
		}

		function validateOneDisciplineArchive ( $cms485_id, $episodeDisciplines ) {
			
			$valid = true;
			
			if ($episodeDisciplines[0]->quantity > 13) {
				
				$visit_13 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(13));
				if (@$visit_13[0]->eval != 'Y') {
					$valid = false;
				}
				
			}
			
			if ($episodeDisciplines[0]->quantity > 19) {
				
				$visit_19 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(19));
				if (@$visit_19[0]->eval != 'Y') {
					$valid = false;
				}
				
			}
			
			return $valid;
			
		}
		
		function validateTwoDiscipline ( $cms485_id, $episodeDisciplines ) {

			$totalVisits = $episodeDisciplines[0]->quantity + $episodeDisciplines[1]->quantity;
			$tempVisitsRange1 = array();
			$tempVisitsRange2 = array();

			if ($totalVisits > 13) { // rango 1
				
				$visit_12 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(12));
				if ($visit_12[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_12[0]->discipline_id] = $visit_12[0]->discipline_id;
				}
				
				$visit_13 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(13));
				if ($visit_13[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_13[0]->discipline_id] = $visit_13[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 19) { // rango 2

				$visit_18 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(18));
				if ($visit_18[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_18[0]->discipline_id] = $visit_18[0]->discipline_id;
				}
				
				$visit_19 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(19));
				if ($visit_19[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_19[0]->discipline_id] = $visit_19[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 13 && $totalVisits < 20 && count($tempVisitsRange1) != 2) {
				return false;
			}
			
			if ($totalVisits > 19 && (count($tempVisitsRange1) != 2  || count($tempVisitsRange2) != 2)) {
				return false;
			}
			
			return true;
			
		}

		function validateTwoDisciplineArchive ( $cms485_id, $episodeDisciplines ) {

			$totalVisits = $episodeDisciplines[0]->quantity + $episodeDisciplines[1]->quantity;
			$tempVisitsRange1 = array();
			$tempVisitsRange2 = array();

			if ($totalVisits > 13) { // rango 1
				
				$visit_12 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(12));
				if ($visit_12[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_12[0]->discipline_id] = $visit_12[0]->discipline_id;
				}
				
				$visit_13 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(13));
				if ($visit_13[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_13[0]->discipline_id] = $visit_13[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 19) { // rango 2

				$visit_18 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(18));
				if ($visit_18[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_18[0]->discipline_id] = $visit_18[0]->discipline_id;
				}
				
				$visit_19 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(19));
				if ($visit_19[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_19[0]->discipline_id] = $visit_19[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 13 && $totalVisits < 20 && count($tempVisitsRange1) != 2) {
				return false;
			}
			
			if ($totalVisits > 19 && (count($tempVisitsRange1) != 2  || count($tempVisitsRange2) != 2)) {
				return false;
			}
			
			return true;
			
		}
		
		function validateThreeDiscipline ( $cms485_id, $episodeDisciplines ) {
			
			$totalVisits = $episodeDisciplines[0]->quantity + $episodeDisciplines[1]->quantity + $episodeDisciplines[2]->quantity;
			$tempVisitsRange1 = array();
			$tempVisitsRange2 = array();

			if ($totalVisits > 13) { // rango 1

				$visit_11 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(11));
				if ($visit_11[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_11[0]->discipline_id] = $visit_11[0]->discipline_id;
				}
								
				$visit_12 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(12));
				if ($visit_12[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_12[0]->discipline_id] = $visit_12[0]->discipline_id;
				}
				
				$visit_13 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(13));
				if ($visit_13[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_13[0]->discipline_id] = $visit_13[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 19) { // rango 2

				$visit_17 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(17));
				if ($visit_17[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_17[0]->discipline_id] = $visit_17[0]->discipline_id;
				}
				
				$visit_18 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(18));
				if ($visit_18[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_18[0]->discipline_id] = $visit_18[0]->discipline_id;
				}
				
				$visit_19 = $this->CI->visitlogmdl->getByEpisodeAndNumber($cms485_id, array(19));
				if ($visit_19[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_19[0]->discipline_id] = $visit_19[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 13 && $totalVisits < 20 && count($tempVisitsRange1) != 3) {
				return false;
			}
			
			if ($totalVisits > 19 && (count($tempVisitsRange1) != 3  || count($tempVisitsRange2) != 3)) {
				return false;
			}
			
			return true;
			
		}

		function validateThreeDisciplineArchive ( $cms485_id, $episodeDisciplines ) {
			
			$totalVisits = $episodeDisciplines[0]->quantity + $episodeDisciplines[1]->quantity + $episodeDisciplines[2]->quantity;
			$tempVisitsRange1 = array();
			$tempVisitsRange2 = array();

			if ($totalVisits > 13) { // rango 1

				$visit_11 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(11));
				if ($visit_11[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_11[0]->discipline_id] = $visit_11[0]->discipline_id;
				}
								
				$visit_12 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(12));
				if ($visit_12[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_12[0]->discipline_id] = $visit_12[0]->discipline_id;
				}
				
				$visit_13 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(13));
				if ($visit_13[0]->eval == 'Y') {
					$tempVisitsRange1[$visit_13[0]->discipline_id] = $visit_13[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 19) { // rango 2

				$visit_17 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(17));
				if ($visit_17[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_17[0]->discipline_id] = $visit_17[0]->discipline_id;
				}
				
				$visit_18 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(18));
				if ($visit_18[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_18[0]->discipline_id] = $visit_18[0]->discipline_id;
				}
				
				$visit_19 = $this->CI->visitlogmdl->getByEpisodeAndNumberArchive($cms485_id, array(19));
				if ($visit_19[0]->eval == 'Y') {
					$tempVisitsRange2[$visit_19[0]->discipline_id] = $visit_19[0]->discipline_id;
				}
				
			}
			
			if ($totalVisits > 13 && $totalVisits < 20 && count($tempVisitsRange1) != 3) {
				return false;
			}
			
			if ($totalVisits > 19 && (count($tempVisitsRange1) != 3  || count($tempVisitsRange2) != 3)) {
				return false;
			}
			
			return true;
			
		}
		
		function validateVisit ( $visit_log_id ) {
			// Remove 13/19 validations 

			/*$visit_log = $this->CI->visitlogmdl->getById($visit_log_id);
			
			$quantity = $this->CI->visitlogmdl->getDisciplineQuantity($visit_log->cms485_id);
			
			if ($quantity && 
				in_array($visit_log->visit_log_number, $this->eval_config_number_plain[count($quantity)]) && 
				$this->validateCalendar($visit_log->cms485_id) == false) {
				return false;
			} 
			*/
			return true;
			
		}

		function validateVisitArchive ( $visit_log_id ) {
			// Remove 13/19 validations 
			/*$visit_log = $this->CI->visitlogmdl->getByIdArchive($visit_log_id);
			
			$quantity = $this->CI->visitlogmdl->getDisciplineQuantityArchive($visit_log->cms485_id);
			
			if ($quantity && 
				in_array($visit_log->visit_log_number, $this->eval_config_number_plain[count($quantity)]) && 
				$this->validateCalendarArchive($visit_log->cms485_id) == false) {
				return false;
			} */
			
			return true;
			
		}
		
		
	}
