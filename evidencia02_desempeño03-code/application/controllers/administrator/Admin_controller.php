<?php

class Admin_Controller extends MY_Controller {
	function __construct(){
		parent::__construct();

		if ($this->getUserId()!=0) {
			redirect();
		}
	}
}
