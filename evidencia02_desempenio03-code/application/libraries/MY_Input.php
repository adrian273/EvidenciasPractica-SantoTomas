<?php

	class MY_Input extends CI_Input {
		
		function MY_Input () {
			parent::CI_Input();
		}
		
		function post($index = '', $xss_clean = FALSE) {
			
			$value = $this->_fetch_from_array($_POST, $index, $xss_clean);
			
			if ($value == "") 
				return NULL;
			else
				return $value;
			
		}
		
	}