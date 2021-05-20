<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Smartie Class
 *
 * @package        CodeIgniter
 * @subpackage     Libraries
 * @category       Smarty
 * @author         Kepler Gelotte
 * @link           http://www.coolphptools.com/codeigniter-smarty
 */
require_once APPPATH . '/third_party/smarty-3.1.38/libs/Smarty.class.php';

class Tpl extends Smarty
{

    public $debug = false;

    public function __construct()
    {
        parent::__construct();

        $config = &get_config();
        $this->template_dir = APPPATH . "views";
        $this->compile_dir = APPPATH . 'cache/';
        if (!is_writable($this->compile_dir)) {
            // make sure the compile directory can be written to
            @chmod($this->compile_dir, 0777);
        }

        // Uncomment these 2 lines to change Smarty's delimiters
        // $this->left_delimiter = '{{';
        // $this->right_delimiter = '}}';
        if (function_exists('site_url')) {
            // URL helper required
            $this->assign("site_url", site_url()); // so we can get the full path to CI easily
        }

        $this->assign("base_url", $config["base_url"]);
        $this->assign("index_url", $config["index_url"]);
        $this->assign("image_url", $config["base_url"] . "style/images/");

        $this->assign('FCPATH', FCPATH); // path to website
        $this->assign('APPPATH', APPPATH); // path to application directory
        $this->assign('BASEPATH', BASEPATH); // path to system directory

        log_message('debug', "Smarty Class Initialized");
    }

    public function setDebug($debug = true)
    {
        $this->debug = $debug;}

    /**
     *  Parse a template using the Smarty engine
     *
     * This is a convenience method that combines assign() and
     * display() into one step.
     *
     * Values to assign are passed in an associative array of
     * name => value pairs.
     *
     * If the output is to be returned as a string to the caller
     * instead of being output, pass true as the third parameter.
     *
     * @access    public
     * @param    string
     * @param    array
     * @param    bool
     * @return    string
     */
    public function view($template, $data = array(), $return = true)
    {
        if (!$this->debug) {
            $this->error_reporting = false;
        }

        $this->error_unassigned = false;

        if (strpos($template, '.') === false) {
            $template .= '.tpl';
        }

        foreach ($data as $key => $val) {
            $this->assign($key, $val);
        }

        if ($return == true) {
            $CI = &get_instance();
            if (method_exists($CI->output, 'set_output')) {
                $CI->output->set_output($this->display($template));
            } else {
                $CI->output->final_output = $this->fetch($template);
            }
            return;
        } else {
            return $this->fetch($template);
        }
    }

    /**
     * @param $include
     * @param $resource_name
     */
    public function assign_include($include, $resource_name)
    {
        $this->assign($include, $resource_name . ".tpl");
    }

    function getoutput ( $resource_name, $params = array() ) {

        if (strpos($resource_name, '.') === false) {
                $resource_name .= '.tpl';
        }
        
        if (is_array($params) && count($params)) {
                foreach ($params as $key => $value) {
                        $this->assign($key, $value);
                }
        }
        
        // check if the template file exists.
        if (!is_file($this->template_dir . $resource_name)) {
             show_error("template: [$resource_name] cannot be found.");
        }
        
        return parent::fetch($resource_name);

    }
}
