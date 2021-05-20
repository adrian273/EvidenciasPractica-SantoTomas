<?php

 class AaviMdl extends Model {

    var $vt122_id;
    var $visit_log_id;
    var $pt_signed_date;
    var $clinician_signature;
    var $clinician_signed_date;
    // var $cavi_notes;
    var $upload_document_name;

	var $diagnoses;
	var $pasa;
	var $service_co_ordinator;
	var $goals_valid_through_date;
	var $client_profile;
	var $tab_113_comm_init_conv;
	var $tab_113_comm_use_single_words;
	var $tab_113_comm_use_short_phrases;
	var $tab_113_comm_use_full_sentences;
	var $tab_113_comm_use_aac;
	var $tab_113_comm_use_asl;
	var $tab_113_comm_appr_into;
	var $tab_113_comm_appr_vocal_vol;
	var $tab_113_comm_intell_speech;
	var $tab_113_comm_appr_rate_speech;
	var $tab_113_comm_oral_motor_coord_diff;
	var $tab_113_comm_articu_speech_diff;
	var $tab_113_comm_use_expr_lang;
	var $tab_113_comm_appr_res_or_accur_recept_lang;
	var $tab_113_comm_exagg_lang;
	var $tab_113_comm_repet_echo_speech;
	var $tab_113_comm_purpos_body_lang;
	var $comm_comments;
	var $tab_114_cogn_awar_surr;
	var $tab_114_cogn_sust_foc_att;
	var $tab_114_cogn_selec_att;
	var $tab_114_cogn_div_att;
	var $tab_114_cogn_alt_att;
	var $tab_114_cogn_decision_making;
	var $tab_114_cogn_reas_prob_solv;
	var $tab_114_cogn_memory_recall;
	var $tab_114_cogn_proprioception;
	var $tab_114_cogn_visual_tracking;
	var $tab_114_cogn_auditory_discri;
	var $tab_114_cogn_ability_seq;
	var $tab_114_cogn_ability_foll_simp_dir;
	var $tab_114_cogn_ability_foll_compl_dir;
	var $cogn_comments;
	var $tab_115_motor_inde_ambu;
	var $tab_115_motor_gross_cordin;
	var $tab_115_motor_gross_strength;
	var $tab_115_motor_gross_balance;
	var $tab_115_motor_obst_nav;
	var $tab_115_motor_fine_coord;
	var $tab_115_motor_fine_strength_grasp;
	var $tab_115_motor_fine_strength_digits;
	var $tab_115_motor_core_strength;
	var $tab_115_motor_muscle_tone;
	var $tab_115_motor_bilateral_coord;
	var $tab_115_motor_eye_hand_coord;
	var $tab_115_motor_use_left_extr_upp;
	var $tab_115_motor_use_right_extr_upp;
	var $tab_115_motor_use_left_extr_low;
	var $tab_115_motor_use_right_extr_low;
	var $tab_115_motor_cross_midl_low;
	var $tab_115_motor_cross_midl_upp;
	var $tab_115_motor_range_motion_low;
	var $tab_115_motor_range_motion_upp;
	var $tab_115_motor_overall_phys_act_level;
	var $motor_comments;
	var $tab_116_senso_1_vision;
	var $tab_116_senso_1_auditory;
	var $tab_116_senso_1_tectile;
	var $tab_116_senso_1_vestibular;
	var $tab_116_senso_1_olfactory;
	var $tab_117_senso_2_self_stim_behav;
	var $tab_117_senso_2_use_adapt_equip;
	var $tab_117_senso_2_ind_req_input;
	var $tab_117_senso_2_ind_req_reg_ext_fac;
	var $tab_117_senso_2_resp_pas_inp;
	var $senso_comments;
	var $tab_118_social_behav_1_self_expr;
	var $tab_118_social_behav_1_self_regul;
	var $tab_118_social_behav_1_use_cop_skill;
	var $tab_118_social_behav_1_self_esteem;
	var $tab_118_social_behav_1_iden_emot_self;
	var $tab_118_social_behav_1_iden_emot_oth;
	var $tab_118_social_behav_1_appr_rang_eff;
	var $tab_118_social_behav_1_appr_emot_resp;
	var $tab_118_social_behav_1_eye_cont_speak;
	var $tab_118_social_behav_1_engage_partic;
	var $tab_118_social_behav_1_abil_share_item;
	var $tab_118_social_behav_1_share_appr_inf_oth;
	var $tab_118_social_behav_1_joint_play_atten;
	var $tab_118_social_behav_1_turn_taking;
	var $tab_118_social_behav_1_compli_pref_tasks;
	var $tab_118_social_behav_1_compli_non_pref_tasks;
	var $tab_118_social_behav_1_use_social_greet;
	var $tab_118_social_behav_1_respo_to_quest;
	var $tab_118_social_behav_1_ask_quest;
	var $tab_118_social_behav_1_abil_antic;
	var $tab_119_obser_behav_2_health_safe_engag;
	var $tab_119_obser_behav_2_satis_cont;
	var $tab_119_obser_behav_2_neutr_amb;
	var $tab_119_obser_behav_2_anxiety_fear;
	var $tab_119_obser_behav_2_depression;
	var $tab_119_obser_behav_2_sadness;
	var $tab_119_obser_behav_2_frustration;
	var $tab_119_obser_behav_2_anger;
	var $tab_119_obser_behav_2_aggression;
	var $tab_119_obser_behav_2_ident_esc;
	var $tab_119_obser_behav_2_dysr_lability;
	var $social_obs_behav_comments;
	var $treatment_recomm;
	var $goal1;
	var $objective1;
	var $goal2;
	var $objective2;
	var $goal3;
	var $objective3;
	var $treatment_plan;
	
	function AaviMdl () {

		parent::Model();
		
	}

	function get ( $visit_log_id ) {
	
		$this->db->where('visit_log_id', $visit_log_id);
	
		$query = $this->db->get('vt122_annual_assessment_visit');
		$row = $query->row();

		array_walk($row, 'convert_special_chars');

		return $row;
	
	}

	function getEmpty ( ) {
		return $this;
	}

	function insert () {
	
		$this->db->insert('vt122_annual_assessment_visit', $this);
	
	}
	
	function update ( $visit_log_id ) {
	
		unset($this->vt122_id);
		
		$this->db->where('visit_log_id', $visit_log_id);
	
		$this->db->update('vt122_annual_assessment_visit', $this);
	
	}
	
	function getPrevious ( $user_id=null, $previous_date, $episode_id, $allepisodes=false ) {
		$soc_id = null;
        if($allepisodes AND $episode_id){      
          $this->db->select('soc_id');
          $this->db->where('cms485_id', $episode_id);
          $query = $this->db->get('cms_485');    
          $row = $query->row_array();
          $soc_id = $row['soc_id'];
        }
        
		$this->db->join('vis_visit_log',   'vis_visit_log.visit_log_id = vt122_annual_assessment_visit.visit_log_id');
		
        if (!is_null($user_id))
		  $this->db->where('visit_user_id', $user_id);
		  $this->db->where('visit_date_time <', $previous_date);
		if(!is_null($soc_id)){
            $this->db->join('cms_485',   'cms_485.soc_id = '.$soc_id);
            $this->db->where('vis_visit_log.cms485_id=cms_485.cms485_id');
            $this->db->group_by('vis_visit_log.visit_log_id');
        }else{
            $this->db->where('cms485_id', $episode_id);
		}
		
		$this->db->order_by('visit_date_time');
		
		$query = $this->db->get('vt122_annual_assessment_visit');
		return $query->result();
		
	}
  
    function getNext ( $user_id=null, $previous_date, $episode_id ) {
		
		$this->db->join('vis_visit_log',   'vis_visit_log.visit_log_id = vt122_annual_assessment_visit.visit_log_id');
		
        if (!is_null($user_id))
		  $this->db->where('visit_user_id', $user_id);
		
        $this->db->where('visit_date_time >', $previous_date);
		$this->db->where('cms485_id', $episode_id);
		
		$this->db->order_by('visit_date_time');
		
		$query = $this->db->get('vt122_annual_assessment_visit');
		return $query->result();
		
	}
	
	function copy ( $destination_visit_log_id, $source_visit_log_id ) {

		$object = new AaviMdl();
		$object1 = $this->get($destination_visit_log_id);
		$object = $this->get($source_visit_log_id);
		
		$object->vt122_id = $object1->vt122_id;
		$object->visit_log_id = $destination_visit_log_id;
	
		$this->db->where('visit_log_id', $destination_visit_log_id);
		$this->db->update('vt122_annual_assessment_visit', $object);
		
	}
	
    function updateFlex($visit_log_id, $attribute, $value, $where = 'visit_log_id') {

        $this->db->where($where, $visit_log_id);
        $this->db->set($attribute, $value);

        $this->db->update('vt122_annual_assessment_visit');

    }

    function delete($visit_log_id) {

        $this->db->where('visit_log_id', $visit_log_id);
        $this->db->delete('vt122_annual_assessment_visit');

    }

 }
