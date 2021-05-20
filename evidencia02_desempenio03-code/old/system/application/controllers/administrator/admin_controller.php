<?php

class Admin_Controller extends MY_Controller {
	function Admin_Controller(){
		parent::MY_Controller();

		if ($this->getUserId()!=0) {
			redirect();
		}
	}
}