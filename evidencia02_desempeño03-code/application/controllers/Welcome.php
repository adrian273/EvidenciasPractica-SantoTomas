<?php
class Welcome extends MY_Controller {

	var $admin_apps = array (
		'Administration' => array (
			'Agency List' => 'administrator/agency',
			'Contractor List' => 'administrator/agency/contractor',
			'Doctor List' => 'administrator/agency/doctor',
			'Profile' => 'administrator/profile',
			'User' => 'administrator/user',
			'User2' => 'administrator/user/user2',
			'Discipline Visits' => 'administrator/discipline',
			'Software Updates' => 'administrator/maintenance',
			'System settings' => 'administrator/system',
			'Dynamic Menu' => 'administrator/management_reports',
			'Audit Log' => 'administrator/auditlog',
			'Fix Visit Log Number' => 'administrator/system/fixVisitLogNumber',
			'FAQ Management' => 'administrator/faq',
			'Dup Doctors' => 'administrator/dupdoctors'
		),
		'Help' => array (
			'Heat Ticket' => 'administrator/ticket',
			'FAQ' => 'faq'
		)
	);

	function __construct() {

		parent :: __construct();
		$this->tpl->assign("resource_path", "welcome");

	}

	function index($open_app = null) {

		if ($this->session->userdata('logged') === TRUE) { // user logged
			if ($this->session->userdata('agency_id') || $this->session->userdata('user_id') == 0) { // agency selected or administrator user
				//echo "in";
				$user = $this->usermdl->getById($this->session->userdata('user_id'));

				if ($this->session->userdata('user_id') == 0) { // loading administrator menu

					$this->tpl->assign("menu", $this->admin_apps);
					//$this->tpl->assign("home_app", "administrator/agency");
					$this->tpl->assign("home_app", "administrator/home");


				} else {

					$last_agreement_id = $this->agreementmdl->getLast()->agreement_id;
					/* if there´s no contract agreement yet (first user login)	 */
					if ($user->contract_flag < $last_agreement_id) {
						$this->session->set_userdata('first_time_login', TRUE);
						/* if current section is an iframe then redirect via javascript, if not then redirect via PHP	 */
						if ($this->uri->segment(1) != '') {
							echo "<script>window.parent.location.href='" . base_url() . "index.php/welcome/user_agreement';</script>";
						} else {
							redirect('welcome/user_agreement');
						}

					}

					$this->load->model("messagemdl");
					$user_id = $this->getUsAgyId();
					$inboxeEmailNew = $this->messagemdl->getCountInboxNew($user_id);

					$comnotesNew = $this->messagemdl->getCountComnotes($user_id);

					/*foreach($inboxeEmailNew as $k=>$v){
					 foreach($v as $k1=>$v1){
					     $emailNew = $v1;
					     }
					 $this->tpl->assign("emailNew", $emailNew);
					}        */

					$this->tpl->assign("countemail", $inboxeEmailNew);
					$this->tpl->assign("countcomnotes", $comnotesNew);

					$this->tpl->assign("menu", $this->loadUserMenu());
					$this->tpl->assign("agency", $this->agencymdl->getByAgencyId($this->getAgencyId()));

					$apph = $this->applicationmdl->getById($user->home_app_id);
					@ $this->tpl->assign("home_app", $apph->application_path);

					$unreadInternalEmail = $this->messagemdl->getCountInboxUnread($user_id);
					//  5949
					if ($unreadInternalEmail > 9 && $this->getAgencyId() != 5949) {
						@ $this->tpl->assign("home_app", 'mail/mail');
					}

					if (!is_null($open_app)) {

						$this->tpl->assign("home_app", str_replace("-", "/", $open_app));

					}

				}



				$us_agy = $this->usagymdl->getByUserId($this->getUserId());
				//				if (count($us_agy) > 1 || $user->tab_005_user_type == 2) {
				//					$this->tpl->assign('multiple_agency', true);
				//				}
				if (count((array) $us_agy) > 1) {
					$this->tpl->assign('multiple_agency', true);
				}

				if ($this->session->userdata('first_time_login')) {
					$this->tpl->assign("home_app", "account/passwordchange");
				}

				$message_count = $this->usermessagemdl->getCountUnread($this->getUsAgyId());
				if ($message_count > 9)
					$message_count = 'm9';
				$this->tpl->assign("message_count", $message_count);

				$this->load->model('us1usercredentialsmdl');
				$this->tpl->assign("alertcredential", "");
				$this->tpl->assign("alertcredentialerror", "");

				$credentials = array();
				if ($this->getUserId() != 0) {
					if ($this->session->userdata('agency_id')) {
						$credentials = $this->us1usercredentialsmdl->getById($this->getUserId(), null, null, $this->session->userdata('agency_id'));
					} else {
						$credentials = $this->us1usercredentialsmdl->getById($this->getUserId());
					}

				}
	            $credentialAlert = false;
	            if(count((array) $credentials) >0){
	                foreach($credentials as $credential){
	                	if ($credential->verify_expiration == 'no') {
	                		continue;
	                	}
				
	                    $currentday = strtotime("now");
	                    $expiration_date = strtotime($credential->expiration_date);
	                    $alert_days = $credential->alert_days * 24 * 60 * 60 ;
	                    $days_left = floor(($expiration_date - $currentday)/(24 * 60 * 60 ));

	                    if($currentday > $expiration_date){
	                    	$us_agy_id = $this->getUsAgyId();
	                        // $this->tpl->assign("alertcredentialerror", "Credentials are expired!");
	                        $this->tpl->assign("alertcredentialerror", "Credentials expired or about to be expired!");
	                        $this->tpl->assign("alertcredentialerrorUrl", base_url() . "index.php/useragency/user_expiredcredential/index/" . $us_agy_id);
	                        $credentialAlert = TRUE;
	                        break;
	                    }elseif($days_left <= $credential->alert_days){
	                    	$us_agy_id = $this->getUsAgyId();
	                        // $this->tpl->assign("alertcredential", "Credentials are about to expire!");
	                        $this->tpl->assign("alertcredential", "Credentials expired or about to be expired!");
	                        $this->tpl->assign("alertcredentialUrl", base_url() . "index.php/useragency/user_expiredcredential/index/". $us_agy_id);
	                        $credentialAlert = TRUE;
	                        break;
	                    }
	                }
	            }
	            if (!$credentialAlert) {
	            	$profile_id = $this->getUserProfileId();
	            	if ($profile_id == 1 OR $profile_id == 3) {
	            		foreach ($this->usagymdl->getByAgencyId($this->getAgencyId()) as $creduser) {
	            			if ($credentialAlert) {
	            				break;
	            			}

	            			// $credentials = $this->us1usercredentialsmdl->getById($creduser->user_id);
	            			$credentials = $this->us1usercredentialsmdl->getExpiredList($creduser->user_id, null, $this->getAgencyId());
	            	
	            			if(count((array) $credentials) >0){
				                foreach($credentials as $credential){
				                	// if ($credential->verify_expiration == 'no') {
				                	// 	continue;
				                	// }
				                    $currentday = strtotime("now");
				                    $expiration_date = strtotime($credential->expiration_date);
				                    $alert_days = $credential->alert_days * 24 * 60 * 60 ;
				                    $days_left = floor(($expiration_date - $currentday)/(24 * 60 * 60 ));

				                    if($currentday > $expiration_date){
				                        // $this->tpl->assign("alertcredentialerror", "Credentials are expired!");
				                        $this->tpl->assign("alertcredentialerror", "Credentials expired or about to be expired!");
				                        // $this->tpl->assign("alertcredentialerrorUrl", base_url() . "index.php/useragency/user");
				                        $this->tpl->assign("alertcredentialerrorUrl", base_url() . "index.php/useragency/user_expiredcredential");
				                        $credentialAlert = TRUE;
				                        break;
				                    }elseif($days_left <= $credential->alert_days){
				                        // $this->tpl->assign("alertcredential", "Credentials are about to expire!");
				                        $this->tpl->assign("alertcredential", "Credentials expired or about to be expired!");
				                        // $this->tpl->assign("alertcredentialUrl", base_url() . "index.php/useragency/user");
				                        $this->tpl->assign("alertcredentialUrl", base_url() . "index.php/useragency/user_expiredcredential");
				                        $credentialAlert = TRUE;
				                        break;
				                    }
				                }
				            }
	            		}
	            	}
	            }
	            $user_agent = $_SERVER['HTTP_USER_AGENT'];
	            if (stripos( $user_agent, 'Chrome') !== false)
				{
					$this->tpl->assign("browser", "chrome");
				}else if (stripos( $user_agent, 'Safari') !== false)
				{
					$this->tpl->assign("browser", "safari");
				}else if (preg_match('/MSIE/i', $user_agent)) {
					$this->tpl->assign("browser", "ie");
			    }else if (preg_match('/Firefox/i', $user_agent)) {
					$this->tpl->assign("browser", "fireFox");
			    }

			    // menu: Manage Doctor Order 2 (ready, pending)
			    if ($this->getAgencyType()=='A') {
			    	$this->tpl->assign("ready_doc_order_count", count($this->visitlogmdl->get_doctororder($this->getAgencyId(), 'A', 1)));
			    	$this->tpl->assign("pending_doc_order_count", count($this->visitlogmdl->get_doctororder($this->getAgencyId(), 'A', 2)));
		    	}

		    	$this->load->model('usagydisciplinemdl');
		    	$user_disciplines = $this->usagydisciplinemdl->getByUser($this->getUsAgyId());
		    	if (count((array) $user_disciplines) > 1) {
		    		$user_discipline_list = array();
		    		foreach ($user_disciplines as $disc) {
		    			$user_discipline_list[$disc->discipline_id] = $disc->description.' ('.$disc->short_description.')';
		    		}
		    		$usagy = $this->usagymdl->getByUsAgyId($this->getUsAgyId());
					$this->tpl->assign("user_discipline_selected", $usagy->discipline_id);
					$this->tpl->assign("user_discipline_list", $user_discipline_list);
					$this->tpl->assign("show_discipline_selection", true);
		    	}

				$this->load->model('agencyproductmdl');
				$agency_products = $this->agencyproductmdl->getProductByAgency($this->getAgencyId());
				if (count((array) $agency_products) > 1) {
					// array_unshift($agency_products, "- Select Line of Business -");
					$this->tpl->assign("agency_products", $agency_products);
					$this->tpl->assign("show_lob_selection", true);
		    	} else if (count((array) $agency_products) == 1) {
					// check if there is only 1 agency_product then assign this to the database and also save this ot the us_Agy table so that could be used while adding the patient
					list($lob, $lob_label) = each($agency_products);
					$this->load->model('agencyproductmdl');
					$agency_products = $this->agencyproductmdl->getProductByAgency($this->getAgencyId());
					$this->session->set_userdata('line_of_business', $lob);
					$this->session->set_userdata('line_of_business_text', $agency_products[$lob]);
					$this->usagymdl->updateFlex($this->getUsAgyId(), 'user_tab111_product_id', $lob);
				}

				$logged_in_us_agy = $this->usagymdl->getByUserAgencyId($this->session->userdata('agency_id'), $this->session->userdata('user_id'));
				if(isset($logged_in_us_agy->tab111_product_id) && $logged_in_us_agy->tab111_product_id > 0) {
					$this->session->set_userdata('line_of_business', $logged_in_us_agy->tab111_product_id);
					$this->session->set_userdata('line_of_business_text', $agency_products[$logged_in_us_agy->tab111_product_id]);
				}

				$this->tpl->assign("logged_in_us_agy", $logged_in_us_agy);
				$this->tpl->assign("user", $user);
				$this->tpl->view("main", $this->lang->language);

			} else { // form to selected agency to work

				$this->premain();

			}

		} else { // user not logged
			$this->load->library('user_agent');
			if($this->agent->is_referral()){
				$this->login();
			} else {
				$this->login();
				// header("Location: http://www.homehealthsoft.com");
			}

		}

	}

  function message(){
    $this->db->select('*');
    $this->db->from('site_message');
    $this->db->order_by('msg_id','desc');
    $this->db->limit(1);
    $query = $this->db->get();

    if($query->num_rows() == 0)
		redirect();

    $row = $query->row_array();
    $this->tpl->assign('message', $row['msg_text']);

		$this->tpl->assign_include("dynamic_tpl", "message");
		$this->tpl->view("sys_msgs/sys_msg", $this->lang->language);

  }

	function login() {

		$rules = array (
			'user_email' => 'required|callback_verify_login',
			'password' => 'required'
		);

		$this->validation->set_rules($rules);
		if ($this->session->userdata('password_reseted') === TRUE) {
			$this->tpl->assign('password_reseted', true);
			$this->session->set_userdata('password_reseted', FALSE);
		}

		if ($this->validation->run() == FALSE) {

			$this->tpl->view("login/login", $this->lang->language);

		} else {

			$this->session->set_userdata('logged', TRUE);
			$this->session->set_userdata('password_reseted', FALSE);

			$user = $this->usermdl->getById($this->session->userdata('user_id'));

			// checking how many agencies is associated
			$us_agy = $this->usagymdl->getByUserId($this->session->userdata('user_id'));
			//			if (count($us_agy) == 1 && $user->tab_005_user_type == 1) {
			//				$this->session->set_userdata('agency_id', $us_agy[0]->agency_id);
			//			}
			if (count((array) $us_agy) == 1) {
				$this->session->set_userdata('agency_id', $us_agy[0]->agency_id);
			}

			$this->load->model('auditlogmdl');
			$this->auditlogmdl->user_id = $this->session->userdata('user_id');
			$this->auditlogmdl->insert();

			// Update login time for individual users login
			$this->usermdl->updateLoginTime($this->session->userdata('user_id'));

			$query = $this->db->get('site_message');
			if($query->num_rows() > 0) {
				//echo "<pre>"; print_r($this->session->userdata); echo "asdf</pre>";
				//echo "I am here in"; exit;
				redirect("/welcome/message");
			} else {
				//echo "<pre>"; print_r($this->session->userdata); echo "asdf</pre>";
				//echo "I am here"; exit;
				/*if (count($us_agy) == 1 AND $us_agy[0]->short_description != 'N/A') {
					if($this->auditlogmdl->getCountByUserId($this->session->userdata('user_id')) == 1) {
						$this->session->set_userdata('first_time_login', TRUE);
						redirect("/account/passwordchange");
					}
				}	*/
				redirect();
			}
		}

	}

	function verify_login($user_email) {

		$user = $this->usermdl->login($this->input->post('user_email'), $this->input->post('password'));
		if (!count((array) $user)) {
			$this->validation->set_message('verify_login', "The user email and password don't match.");
			return FALSE;
		} else {
			$this->session->set_userdata('user_id', $user->user_id);
			return TRUE;
		}

	}

	function premain() {

		$rules = array (
			'agency_id' => 'required'
		);

		$this->validation->set_rules($rules);

		if ($this->validation->run() == FALSE) {

			$this->tpl->assign('tab_page', true);
			$this->tpl->assign("user", $this->usermdl->getById($this->getUserId()));
			$this->tpl->assign_include('dynamic_tpl', 'login/select_agency');
			$this->tpl->view("parts/ibase", $this->lang->language);

		} else {

			$this->session->set_userdata('agency_id', $this->input->post('agency_id'));
			$this->index();

		}

	}

	function premainagency() {

		$this->tpl->assign('tab_page', true);
		$this->tpl->assign("no_grid_buttons", true);
		//		$this->tpl->assign("noedit", true);
		$this->tpl->assign_include("filter_tpl", "login/gfilter1");
		$this->tpl->assign('resource_grid', 'selectAgencyGrid');
		$this->tpl->assign_include("dynamic_tpl", "parts/gbase");
		$this->tpl->view("parts/ibase", $this->lang->language);

	}

	function selectAgencyGrid() {

		$agency_types = $this->lang->line('agency_type_list');
		$status = $this->lang->line('agency_status_list');

		$this->xml->root_name = "rows";
		$document = $this->xml->Document();

		$head = $this->xml->Element("head");
		$head->appendChild($this->xml->Element("column", "width=*", "Agency Name"));
		$head->appendChild($this->xml->Element("column", "width=*", "Agency Type"));
		$head->appendChild($this->xml->Element("column", "width=*", "Contact Name"));
		$head->appendChild($this->xml->Element("column", "width=*", "Contact Phone"));
		$head->appendChild($this->xml->Element("column", "width=*;align=center", "Nbr. Current Patients"));
		$head->appendChild($this->xml->Element("column", "width=8;align=center;type=link", "Actions"));
		$head->appendChild($this->xml->Element("column", "width=8;align=center;type=link", "#cspan"));
		
		//$head->appendChild($this->xml->Element("settings", null, $this->xml->Element("colwidth", null, "%")));
		$head->appendChild($this->xml->config());
		$document->appendChild($head);

		foreach ($this->usagymdl->getByUserId($this->session->userdata('user_id'), $this->input->post('keyword'), $this->input->post('search_type')) as $agency) {

			$cell = $this->xml->Element("row", "id=" . $agency->agency_id);
			$cell->appendChild($this->xml->Element("cell", null, $agency->agency_name));
			$cell->appendChild($this->xml->Element("cell", null, $agency_types[$agency->agency_type]));
			$cell->appendChild($this->xml->Element("cell", null, $agency->contact_name));
			$cell->appendChild($this->xml->Element("cell", null, $agency->contact_phone));

			$this->session->set_userdata('agency_id', $agency->agency_id);
			$cell->appendChild($this->xml->Element("cell", null, count($this->getPatients())));
			$this->session->set_userdata('agency_id', 0);

			if ($agency->profile_id == 1) {
				$cell->appendChild($this->xml->Element("cell", null, "Edit^" . $this->config->config['index_url'] . "welcome/selectagency/" . $agency->agency_id . "/agencyprofile-base^_parent"));
			} else {
				$cell->appendChild($this->xml->Element("cell", null, null));
			}

			$cell->appendChild($this->xml->Element("cell", null, "Select^" . $this->config->config['index_url'] . "welcome/selectagency/" . $agency->agency_id . "^_parent"));

			$document->appendChild($cell);

		}
		$this->xml->create($document);

	}

	function selectagency($agency_id, $open_app = null) {

		if ($this->usagymdl->getByUserAgencyId($agency_id, $this->getUserId())) {

			$this->session->set_userdata('agency_id', $agency_id);

		}

		echo "<SCRIPT>parent.document.location = '" . $this->config->config['index_url'] . "welcome/index/" . $open_app . "'</SCRIPT>";

	}

	function selectpatient($patient_id) {

		$patient = $this->patientmdl->getById($patient_id);

		$this->selectagency($patient->agency_id, "patient-mypatient-edit-" . $patient_id);

	}

	function premainpatient() {

		$this->tpl->assign('tab_page', true);
		$this->tpl->assign("no_grid_buttons", true);
		$this->tpl->assign("resource_edit", "selectpatient");
		$this->tpl->assign_include("filter_tpl", "login/gfilter2");
		$this->tpl->assign('resource_grid', 'selectPatientGrid');
		$this->tpl->assign_include("dynamic_tpl", "parts/gbase");
		$this->tpl->view("parts/ibase", $this->lang->language);

	}

	function selectPatientGrid() {

		$agency_types = $this->lang->line('agency_type_list');
		$status = $this->lang->line('agency_status_list');

		$this->xml->root_name = "rows";
		$document = $this->xml->Document();

		$head = $this->xml->Element("head");
		$head->appendChild($this->xml->Element("column", "width=*", "Patient Name"));
		$head->appendChild($this->xml->Element("column", "width=*", "Agency Name"));
		$head->appendChild($this->xml->Element("column", "width=*", "Agency Type"));
		$head->appendChild($this->xml->Element("column", "width=*", "Case Manager"));
		$head->appendChild($this->xml->Element("column", "width=*", "Contact Name"));
		$head->appendChild($this->xml->Element("column", "width=*", "Contact Phone"));
		$head->appendChild($this->xml->Element("column", "width=*;align=center", "Status"));
		$head->appendChild($this->xml->Element("column", "width=10;align=center;type=link", "Actions"));
		//$head->appendChild($this->xml->Element("settings", null, $this->xml->Element("colwidth", null, "%")));
		$head->appendChild($this->xml->config());
		$document->appendChild($head);

		foreach ($this->patientmdl->getByUserId($this->getUserId(), $this->input->post('keyword'), $this->input->post('search_type'), $this->input->post('agency_type')) as $patient) {

			$agency = $this->agencymdl->getByAgencyId($patient->agency_id);
			$soc = $this->socmdl->getCurrent($patient->patient_id);
			$case_manager = $this->usagymdl->getByUsAgyId($soc->case_manager_user_id);

			$cell = $this->xml->Element("row", "id=" . $patient->patient_id);
			$cell->appendChild($this->xml->Element("cell", null, $patient->complete_name));
			$cell->appendChild($this->xml->Element("cell", null, $agency->agency_name));
			$cell->appendChild($this->xml->Element("cell", null, $agency_types[$agency->agency_type]));
			$cell->appendChild($this->xml->Element("cell", null, (count($case_manager)) ? $case_manager->complete_name : null));
			$cell->appendChild($this->xml->Element("cell", null, $agency->contact_name));
			$cell->appendChild($this->xml->Element("cell", null, $agency->contact_phone));
			$cell->appendChild($this->xml->Element("cell", null, $patient->tab_description));
			$cell->appendChild($this->xml->Element("cell", null, "Pt Calendar^" . $this->config->config['index_url'] . "welcome/selectagency/" . $patient->agency_id . "/patient-patient-edit-" . $patient->patient_id . "--b6^_parent"));
			$document->appendChild($cell);

		}
		$this->xml->create($document);
	}

	function loadUserMenu() {

		$menu = array ();

		$us_agy = $this->usagymdl->getByUserAgencyId($this->session->userdata('agency_id'), $this->session->userdata('user_id'));

		$groups = $this->profilegroupmdl->getByProfile($us_agy->profile_id);

		if (count((array) $groups)) {

			foreach ($groups as $group) {

				$apps = $this->profilegroupappmdl->getByProfileGroup($group->profile_group_id);

				if (count((array) $apps)) {

					$aux = array ();

					foreach ($apps as $app) {

						$aux[$app->application_name] = $app->application_path;

					}

					$menu[$group->group_name] = $aux;

				}

			}

		}

		return $menu;

	}

	function logout() {

		$this->session->sess_destroy();
		$this->session->set_userdata('logged', FALSE);

		redirect();

	}

	function verify_remember($user_email) {

		$user = $this->usermdl->getByEmail($user_email);
		if (!count((array) $user)) {
			$this->validation->set_message('verify_login', "The user doesn't existe. Try again!");
			return FALSE;
		} else {
			$this->session->set_userdata('user_id', $user->user_id);
			return TRUE;
		}

	}

	function forgot() {

		$rules = array (
			'user_email' => 'required|callback_verify_remember'
		);

		$this->validation->set_rules($rules);

		if ($this->validation->run() == FALSE) {

			$this->tpl->view("login/forgot", $this->lang->language);

		} else {

			$pass = passgen();
			$user = $this->usermdl->getByEmail($this->input->post('user_email'));

			// updating new password
			$this->usermdl->updatePassword($user->user_id, $pass);

			// sending new password email
			$emailtext = $this->emailtextmdl->get(1);

			$body = str_replace('{user_first_name}', $user->first_name, $emailtext->email_content);
			$body = str_replace('{user_password}', $pass, $body);

			$this->load->library('email');

			$this->email->from('noreply@homehealthsoft.com', 'HHS Support');
			$this->email->to($user->user_email);
			$this->email->subject($emailtext->email_subject);

			$body = "** This is a no-reply email ** - Please respond through the HHS application internal email system if necessary\n\n".$body;
    		// $this->email->set_mailtype("html");

			$this->email->message($body);
			$this->email->send();

			$_POST = array ();
			$this->session->set_userdata('password_reseted', TRUE);
			//$this->tpl->assign('password_reseted', true);
			redirect();

			//$this->index();

		}

	}

	function ffields($table) {

		$fields = $this->db->list_fields($table);

		foreach ($fields as $field) {

			echo "var \$" . $field . ";<br>";

		}

	}
	function user_agreement() {
		/* get last agreement  */
		$agreement = $this->agreementmdl->getLast();

		if ($this->input->post('iagree') && $this->input->post('submit')) {
			$this->usermdl->submitContractAgreement($this->getUserId(), $agreement->agreement_id);
			redirect();
		}
		elseif ($this->input->post('cancel')) {
			redirect('welcome/logout');
		}
		$this->tpl->assign("terms", $agreement->agreement_text);
		$this->tpl->assign_include("dynamic_tpl", "sys_msgs/terms");
		$this->tpl->view("sys_msgs/sys_msg", $this->lang->language);
	}

	function change_usagy_discipline() {
		if ($this->session->userdata('logged') === TRUE) {
			$discipline_id = $this->input->post('discipline_id');
			$this->load->model('usagydisciplinemdl');
			if($this->usagydisciplinemdl->isExist($this->getUsAgyId(), $discipline_id)){
				$this->usagymdl->updateFlex($this->getUsAgyId(), 'discipline_id', $discipline_id);
				echo '1'; exit();
			}
		}

		echo "0";
		exit();
	}

	function change_usagy_lob() {

		if ($this->session->userdata('logged') === TRUE) {
			$lob = $this->input->post('lob');
			$this->load->model('agencyproductmdl');
			$agency_products = $this->agencyproductmdl->getProductByAgency($this->getAgencyId());
			$this->session->set_userdata('line_of_business', $lob);
			$this->session->set_userdata('line_of_business_text', $agency_products[$lob]);
			$this->usagymdl->updateFlex($this->getUsAgyId(), 'user_tab111_product_id', $lob);
			echo '1'; exit();
		}

		echo "0";
		exit();
	}
}
