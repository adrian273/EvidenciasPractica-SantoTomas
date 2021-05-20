<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* Loads and instantiates fpdf class
*
* @access    private called by the app controller
*/    

if ( ! class_exists('fpdf'))
{
    require_once(BASEPATH.'libraries/fpdf'.EXT);
}

$obj =& get_instance();
$obj->fpdf = new Fpdf();
$obj->ci_is_loaded[] = 'fpdf';