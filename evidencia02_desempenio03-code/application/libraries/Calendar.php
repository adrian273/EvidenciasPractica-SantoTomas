<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Calendar Class
 *
 * This class enables the creation of calendars
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/calendar.html
 */
class CI_Calendar {

	var $CI;
	var $lang;
	var $local_time;
	var $template		= '';
	var $start_day		= 'sunday';
	var $month_type 	= 'long';
	var $day_type		= 'long';
	var $show_next_prev	= FALSE;
	var $next_prev_url	= '';

	/**
	 * Constructor
	 *
	 * Loads the calendar language file and sets the default time reference
	 *
	 * @access	public
	 */
	function __construct($config = array())
	{		
		$this->CI =& get_instance();
		
		if ( ! in_array('calendar_lang'.EXT, $this->CI->lang->is_loaded, TRUE))
		{
			$this->CI->lang->load('calendar');
		}

		$this->local_time = time();
		
		if (count($config) > 0)
		{
			$this->initialize($config);
		}
		
		log_message('debug', "Calendar Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Initialize the user preferences
	 *
	 * Accepts an associative array as input, containing display preferences
	 *
	 * @access	public
	 * @param	array	config preferences
	 * @return	void
	 */	
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Generate the calendar
	 *
	 * @access	public
	 * @param	integer	the year
	 * @param	integer	the month
	 * @param	array	the data to be shown in the calendar cells
	 * @return	string
	 */
	function generate($year = '', $month = '', $data = array())
	{
		if ($year == '')
			$year  = date("Y", $this->local_time);
			
		if ($month == '')
			$month = date("m", $this->local_time);
			
 		if (strlen($year) == 1)
			$year = '200'.$year;
		
 		if (strlen($year) == 2)
			$year = '20'.$year;

 		if (strlen($month) == 1)
			$month = '0'.$month;
		
		$adjusted_date = $this->adjust_date($month, $year);
		
		$month	= $adjusted_date['month'];
		$year	= $adjusted_date['year'];
		
		// Determine the total days in the month
		$total_days = $this->get_total_days($month, $year);
						
		// Set the starting day of the week
		$start_days	= array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
		$start_day = ( ! isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];
		
		// Set the starting day number
		$local_date = mktime(12, 0, 0, $month, 1, $year);
		$date = getdate($local_date);
		$day  = $start_day + 1 - $date["wday"];
		
		while ($day > 1)
		{
			$day -= 7;
		}
		
		// Set the current month/year/day
		// We use this to determine the "today" date
		$cur_year	= date("Y", $this->local_time);
		$cur_month	= date("m", $this->local_time);
		$cur_day	= date("j", $this->local_time);
		
		$is_current_month = ($cur_year == $year AND $cur_month == $month) ? TRUE : FALSE;
	
		// Generate the template data array
		$this->parse_template($this->default_template());
	
		// Begin building the calendar output						
		$out = $this->temp['table_open'];
		$out .= "\n";	

		$out .= "\n";		
		$out .= $this->temp['heading_row_start'];
		$out .= "\n";
		
		// "previous" month link
		if ($this->show_next_prev == TRUE)
		{
			// Add a trailing slash to the  URL if needed
			$this->next_prev_url = preg_replace("/(.+?)\/*$/", "\\1/",  $this->next_prev_url);
		
			$adjusted_date = $this->adjust_date($month - 1, $year);
			$out .= str_replace('{previous_url}', $this->next_prev_url.$adjusted_date['year'].'/'.$adjusted_date['month'], $this->temp['heading_previous_cell']);
			$out .= "\n";
		}

		// Heading containing the month/year
		$colspan = ($this->show_next_prev == TRUE) ? 5 : 7;
		
		$this->temp['heading_title_cell'] = str_replace('{colspan}', $colspan, $this->temp['heading_title_cell']);
		$this->temp['heading_title_cell'] = str_replace('{heading}', $this->get_month_name($month)."&nbsp;".$year, $this->temp['heading_title_cell']);
		
		$out .= $this->temp['heading_title_cell'];
		$out .= "\n";

		// "next" month link
		if ($this->show_next_prev == TRUE)
		{		
			$adjusted_date = $this->adjust_date($month + 1, $year);
			$out .= str_replace('{next_url}', $this->next_prev_url.$adjusted_date['year'].'/'.$adjusted_date['month'], $this->temp['heading_next_cell']);
		}

		$out .= "\n";		
		$out .= $this->temp['heading_row_end'];
		$out .= "\n";

		// Write the cells containing the days of the week
		$out .= "\n";	
		$out .= $this->temp['week_row_start'];
		$out .= "\n";

		$day_names = $this->get_day_names();

		for ($i = 0; $i < 7; $i ++)
		{
			$out .= str_replace('{week_day}', $day_names[($start_day + $i) %7], $this->temp['week_day_cell']);
		}

		$out .= "\n";
		$out .= $this->temp['week_row_end'];
		$out .= "\n";
		
		while ($day <= $total_days)
		{
			$out .= "\n";
			$out .= $this->temp['cal_row_start'];
			$out .= "\n";

			for ($i = 0; $i < 7; $i++)
			{
				if ($day > 0 AND $day <= $total_days)
				{
					$out .= ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_start_today'] : $this->temp['cal_cell_start'];
					
					// Cells with no content
					$temp = $this->temp['cal_cell_content'];
					$out .= str_replace('{day_id}', (strlen($day) == 1 ? '0' . $day : $day), $temp);
				}
				else
				{
					// Blank cells
					$out .= $this->temp['cal_cell_start_empty'];
				}
				
				$out .=  "\n" . $this->temp['cal_cell_end'] . "\n";			  	
				$day++;
			}
			
			$out .= "\n";		
			$out .= $this->temp['cal_row_end'];
			$out .= "\n";		
		}

		$out .= "\n";		
		$out .= $this->temp['table_close'];

		return $out;
		
	}
	
	// --------------------------------------------------------------------

	function generate2 ( $start_date, $end_date, $data = array() ) {
		
		$this->parse_template($this->patient_template());
		$today = mysql_to_unix(date('Y-m-d'));
		
		$start_date_unix = mysql_to_unix($start_date);
		$end_date_unix = mysql_to_unix($end_date);

		$date_30_day_ago_unix = strtotime("-29 days",strtotime($end_date));
		
		$sdate = getdate($start_date_unix);
		$edate = getdate($end_date_unix);
		
		if ($sdate["wday"] > 0) {
			$start_date_unix = $start_date_unix - ($sdate["wday"] * 86400);
		}
		if ($edate["wday"] < 7) {
			$end_date_unix = $end_date_unix + ((6 - $edate["wday"]) * 86400);
		}
		
		// Begin building the calendar output						
		$out = $this->temp['table_open'];
		
		$a = 0;
		$i = $start_date_unix;
		$week = 1;
		while ($i <= $end_date_unix) {
			
			$cdate = getdate($i);
			
			if ($cdate["wday"] == 0) {
				$out .= $this->temp['cal_row_start'];
				$out .= str_replace('{number}', $week++, $this->temp['week_num_cell']);
			}
			
			if ($i < mysql_to_unix($start_date) || $i > mysql_to_unix($end_date)) {
				$out .= $this->temp['cal_cell_start_empty'];
			}
			
			if (mysql_to_unix($start_date) <= $i && $i <= mysql_to_unix($end_date)) {

					$out .= ($i == $today) ? $this->temp['cal_cell_start_today'] : ($i >= $date_30_day_ago_unix ? $this->temp['cal_cell_start_last_30_day'] : $this->temp['cal_cell_start']) ;
					
					$temp = $this->temp['cal_cell_content'];
					$temp = str_replace('{day}', $this->get_month_name($cdate['mon']) . "/" . $cdate['mday'], $temp);
					$temp = str_replace('{day_id}', standard_date($i, 'USA_DATE'), $temp);
					
					$out .= $temp;


			}

			$out .=  "\n" . $this->temp['cal_cell_end'] . "\n";	
			
			if ($cdate["wday"] == 6) {
				$out .= $this->temp['cal_row_end'];
			}

			$i = $this->add_days($i, 1);
			
		}

		$out .= $this->temp['table_close'];
		
		return $out;
		
	}

	/**
	 * Generate the calendar
	 *
	 * @access	public
	 * @param	integer	the year
	 * @param	integer	the month
	 * @param	array	the data to be shown in the calendar cells
	 * @return	string
	 */
	function generate3($year = '', $month = '', $data = array())
	{
		// added by ksa 
		//echo "<pre>"; print_r($data); echo "</pre>";
		if ($year == '')
			$year  = date("Y", $this->local_time);
			
		if ($month == '')
			$month = date("m", $this->local_time);
			
 		if (strlen($year) == 1)
			$year = '200'.$year;
		
 		if (strlen($year) == 2)
			$year = '20'.$year;

 		if (strlen($month) == 1)
			$month = '0'.$month;
		
		$adjusted_date = $this->adjust_date($month, $year);
		
		$month	= $adjusted_date['month'];
		$year	= $adjusted_date['year'];
		
		// Determine the total days in the month
		$total_days = $this->get_total_days($month, $year);
						
		// Set the starting day of the week
		$start_days	= array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
		$start_day = ( ! isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];
		
		// Set the starting day number
		$local_date = mktime(12, 0, 0, $month, 1, $year);
		$date = getdate($local_date);
		$day  = $start_day + 1 - $date["wday"];
		
		while ($day > 1)
		{
			$day -= 7;
		}
		
		// Set the current month/year/day
		// We use this to determine the "today" date
		$cur_year	= date("Y", $this->local_time);
		$cur_month	= date("m", $this->local_time);
		$cur_day	= date("j", $this->local_time);
		
		$is_current_month = ($cur_year == $year AND $cur_month == $month) ? TRUE : FALSE;
	
		// Generate the template data array
		$this->parse_template($this->altern_therapy_template());
	
		// Begin building the calendar output						
		$out = $this->temp['table_open'];
		$out .= "\n";	

		$out .= "\n";		
		$out .= $this->temp['heading_row_start'];
		$out .= "\n";
		
		// "previous" month link
		if ($this->show_next_prev == TRUE)
		{
			// Add a trailing slash to the  URL if needed
			$this->next_prev_url = preg_replace("/(.+?)\/*$/", "\\1/",  $this->next_prev_url);
		
			$adjusted_date = $this->adjust_date($month - 1, $year);
			$out .= str_replace('{previous_url}', $this->next_prev_url.$adjusted_date['year'].'/'.$adjusted_date['month'], $this->temp['heading_previous_cell']);
			$out .= "\n";
		}

		// Heading containing the month/year
		$colspan = ($this->show_next_prev == TRUE) ? 5 : 7;
		
		$this->temp['heading_title_cell'] = str_replace('{colspan}', $colspan, $this->temp['heading_title_cell']);
		$this->temp['heading_title_cell'] = str_replace('{heading}', $this->get_month_name($month)."&nbsp;".$year, $this->temp['heading_title_cell']);
		
		$out .= $this->temp['heading_title_cell'];
		$out .= "\n";

		// "next" month link
		if ($this->show_next_prev == TRUE)
		{		
			$adjusted_date = $this->adjust_date($month + 1, $year);
			$out .= str_replace('{next_url}', $this->next_prev_url.$adjusted_date['year'].'/'.$adjusted_date['month'], $this->temp['heading_next_cell']);
		}

		$out .= "\n";		
		$out .= $this->temp['heading_row_end'];
		$out .= "\n";

		// Write the cells containing the days of the week
		$out .= "\n";	
		$out .= $this->temp['week_row_start'];
		$out .= "\n";

		$day_names = $this->get_day_names();

		for ($i = 0; $i < 7; $i ++)
		{
			$out .= str_replace('{week_day}', $day_names[($start_day + $i) %7], $this->temp['week_day_cell']);
		}

		$out .= "\n";
		$out .= $this->temp['week_row_end'];
		$out .= "\n";
		
		while ($day <= $total_days)
		{
			$out .= "\n";
			$out .= $this->temp['cal_row_start'];
			$out .= "\n";

			for ($i = 0; $i < 7; $i++)
			{
				
				if ($day > 0 AND $day <= $total_days)
				{
					if(
						strtotime($year . "-" . $month . "-" . $day) > strtotime($data['end_date']) && 
						$data['us_agy']->profile_id == 2 && 
						$data['pat_contractor']->company_patient_status == 7
					) { // added by ksa on apr 14, 2019
						$out .= ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_start_today'] : $this->temp['cal_cell_start'];
						// Cells with no content
						$temp = $this->temp['cal_cell_no_content'];
						$temp = str_replace('{day}', $this->get_month_name($month) . "/" . $day, $temp);
						$temp_date = mktime(12, 0, 0, $month, $day, $year);
						$temp = str_replace('{day_id}', standard_date($temp_date, 'USA_DATE'), $temp);
						$out .= $temp;					
					} else {
						$out .= ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_start_today'] : $this->temp['cal_cell_start'];
						
						// Cells with no content
						$temp = $this->temp['cal_cell_content'];
						// $local_date = mktime(12, 0, 0, $month, 1, $year);
						$temp = str_replace('{day}', $this->get_month_name($month) . "/" . $day, $temp);
						$temp_date = mktime(12, 0, 0, $month, $day, $year);
						$temp = str_replace('{day_id}', standard_date($temp_date, 'USA_DATE'), $temp);
						$out .= $temp;
					}
					// $out .= str_replace('{day_id}', (strlen($day) == 1 ? '0' . $day : $day), $temp);
				}
				else
				{
					// Blank cells
					$out .= $this->temp['cal_cell_start_empty'];
				}
				
				$out .=  "\n" . $this->temp['cal_cell_end'] . "\n";			  	
				$day++;
			}
			
			$out .= "\n";		
			$out .= $this->temp['cal_row_end'];
			$out .= "\n";		
		}

		$out .= "\n";		
		$out .= $this->temp['table_close'];

		return $out;
		
	}
	


	/**
	 * Get Month Name
	 *
	 * Generates a textual month name based on the numeric
	 * month provided.
	 *
	 * @access	public
	 * @param	integer	the month
	 * @return	string
	 */
	function get_month_name($month)
	{
		
 		if (strlen($month) == 1)
			$month = '0'.$month;
			
		if ($this->month_type == 'short')
		{
			$month_names = array('01' => 'cal_jan', '02' => 'cal_feb', '03' => 'cal_mar', '04' => 'cal_apr', '05' => 'cal_may', '06' => 'cal_jun', '07' => 'cal_jul', '08' => 'cal_aug', '09' => 'cal_sep', '10' => 'cal_oct', '11' => 'cal_nov', '12' => 'cal_dec');
		}
		else
		{
			$month_names = array('01' => 'cal_january', '02' => 'cal_february', '03' => 'cal_march', '04' => 'cal_april', '05' => 'cal_may', '06' => 'cal_june', '07' => 'cal_july', '08' => 'cal_august', '09' => 'cal_september', '10' => 'cal_october', '11' => 'cal_november', '12' => 'cal_december');
		}
		
		$month = $month_names[$month];
		
		if ($this->CI->lang->line($month) === FALSE)
		{
			return ucfirst(str_replace('cal_', '', $month));
		}

		return $this->CI->lang->line($month);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Get Day Names
	 *
	 * Returns an array of day names (Sunday, Monday, etc.) based
	 * on the type.  Options: long, short, abrev
	 *
	 * @access	public
	 * @param	string
	 * @return	array
	 */
	function get_day_names($day_type = '')
	{
		if ($day_type != '')
			$this->day_type = $day_type;
	
		if ($this->day_type == 'long')
		{
			$day_names = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
		}
		elseif ($this->day_type == 'short')
		{
			$day_names = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
		}
		else
		{
			$day_names = array('su', 'mo', 'tu', 'we', 'th', 'fr', 'sa');
		}
	
		$days = array();
		foreach ($day_names as $val)
		{			
			$days[] = ($this->CI->lang->line('cal_'.$val) === FALSE) ? ucfirst($val) : $this->CI->lang->line('cal_'.$val);
		}
	
		return $days;
	}
 	
	// --------------------------------------------------------------------

	/**
	 * Adjust Date
	 *
	 * This function makes sure that we have a valid month/year.
	 * For example, if you submit 13 as the month, the year will
	 * increment and the month will become January.
	 *
	 * @access	public
	 * @param	integer	the month
	 * @param	integer	the year
	 * @return	array
	 */
	function adjust_date($month, $year)
	{
		$date = array();

		$date['month']	= $month;
		$date['year']	= $year;

		while ($date['month'] > 12)
		{
			$date['month'] -= 12;
			$date['year']++;
		}

		while ($date['month'] <= 0)
		{
			$date['month'] += 12;
			$date['year']--;
		}

		if (strlen($date['month']) == 1)
		{
			$date['month'] = '0'.$date['month'];
		}

		return $date;
	}
 	
	// --------------------------------------------------------------------

	/**
	 * Total days in a given month
	 *
	 * @access	public
	 * @param	integer	the month
	 * @param	integer	the year
	 * @return	integer
	 */
	function get_total_days($month, $year)
	{
		$days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		if ($month < 1 OR $month > 12)
		{
			return 0;
		}

		// Is the year a leap year?
		if ($month == 2)
		{
			if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0))
			{
				return 29;
			}
		}

		return $days_in_month[$month - 1];
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set Default Template Data
	 *
	 * This is used in the event that the user has not created their own template
	 *
	 * @access	public
	 * @return array
	 */
	function default_template()
	{
		return  array (
						'table_open' 				=> '<table border="0" cellpadding="1" cellspacing="1" width="100%" bgcolor="#d9e4f1">',
						'heading_row_start' 		=> '',
						'heading_previous_cell'		=> '',
						'heading_title_cell' 		=> '',
						'heading_next_cell' 		=> '',
						'heading_row_end' 			=> '',
						'week_row_start' 			=> '',
						'week_day_cell' 			=> '',
						'week_row_end' 				=> '',
						'cal_row_start' 			=> '<tr>',
						'cal_cell_start_empty'		=> '<td valign="top" class="empty_day_cell"><div>',
						
						'cal_cell_start' 			=> '<td class="day_cell2"   valign="top">',
						'cal_cell_start_last_30_day'=> '<td class="day_cell2 last_30_day"   valign="top">',
						'cal_cell_start_today'		=> '<td class="today_cell" valign="top">',
						
						'cal_cell_content'			=> '<div class="div_cell"><div style="float: left; height: 18px"><input type="checkbox" name="cal_day" value="{day_id}"></div>' .
													   //'<a onclick="addEvent(\'{day_id}\', this)" style="cursor: pointer">{day_id}</a></div>' .
													   '{day_id}</div>' .
													   '<div id="cttt_{day_id}" class="div_cttt_calu">' .
													   '<div id="up_{day_id}" class="div_up" style="display: none;" onmouseover="changeBackGround(this, \'#edf1f8\')" onmouseout="changeBackGround(this, \'#ffffff\')" onmousedown="scrollDivUp(\'ctt_{day_id}\')"><img src="style/images/blank.gif"></div>' .
													   '<div id="ctt_{day_id}"></div>' .
													   '<div id="dw_{day_id}" class="div_dw" style="display: none;" onmouseover="changeBackGround(this, \'#edf1f8\')" onmouseout="changeBackGround(this, \'#ffffff\')" onmousedown="scrollDivDown(\'ctt_{day_id}\')"><img src="style/images/blank.gif"></div>',
													   
						'cal_cell_content_today'	=> '<a>{day}</a></div><div>{content}',
						
						'content_div'				=> '<div onclick="ToolTipHelp()" style="position: relative" class="div_event">{content}</div>',
						
						'cal_cell_no_content'		=> '{day}',
						'cal_cell_no_content_today'	=> '{day}',
						'cal_cell_blank'			=> '',
						
						'cal_cell_end'				=> '</div></td>',
						
						'cal_cell_end_today'		=> '</td>',
						'cal_row_end'				=> '</tr>',
						'table_close'				=> '</table>',
						'week_num_cell'				=> '<td width="30px" align="center" nowrap><b>{number}</b></td>'
					);	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set Default Template Data
	 *
	 * This is used in the event that the user has not created their own template
	 *
	 * @access	public
	 * @return array
	 */
	function patient_template()
	{
		return  array (
						'table_open' 				=> '<table border="0" cellpadding="1" cellspacing="1" height="100%" width="100%" bgcolor="#d9e4f1">',
						'heading_row_start' 		=> '',
						'heading_previous_cell'		=> '',
						'heading_title_cell' 		=> '',
						'heading_next_cell' 		=> '',
						'heading_row_end' 			=> '',
						'week_row_start' 			=> '',
						'week_day_cell' 			=> '',
						'week_row_end' 				=> '',
						'cal_row_start' 			=> '<tr>',
						'cal_cell_start_empty'		=> '<td valign="top" class="empty_day_cell"><div>',
						
						'cal_cell_start' 			=> '<td class="day_cell"   valign="top">',
						'cal_cell_start_last_30_day'=> '<td class="day_cell last_30_day"   valign="top">',
						'cal_cell_start_today'		=> '<td class="today_cell" valign="top" oonclick="setCheck(\'cb_{day_id}\', !isChecked(\'cb_{day_id}\'))">',
						
						'cal_cell_content'			=> '<div class="div_cell"><div style="float: left; height: 18px"><input type="checkbox" name="cal_day" value="{day_id}" id="cb_{day_id}"></div>' .
													   '<div style="float: right; width: 80%" onclick="setCheck(\'cb_{day_id}\', !isChecked(\'cb_{day_id}\'))"><a onclick="addEvent(\'{day_id}\', this)" style="cursor: pointer">{day}</a></div></div>' .
													   '<div id="cttt_{day_id}" class="div_cttt" onclick="setCheck(\'cb_{day_id}\', !isChecked(\'cb_{day_id}\'))">' .
													   '<div id="up_{day_id}" class="div_up" style="display: none;" onmouseover="changeBackGround(this, \'#edf1f8\')" onmouseout="changeBackGround(this, \'#ffffff\')" onmousedown="scrollDivUp(\'ctt_{day_id}\')"><img src="style/images/blank.gif"></div>' .
													   '<div id="ctt_{day_id}"></div>' .
													   '<div id="dw_{day_id}" class="div_dw" style="display: none;" onmouseover="changeBackGround(this, \'#edf1f8\')" onmouseout="changeBackGround(this, \'#ffffff\')" onmousedown="scrollDivDown(\'ctt_{day_id}\')"><img src="style/images/blank.gif"></div>',
													   
						'cal_cell_content_today'	=> '<a>{day}</a></div><div>{content}',
						
						'content_div'				=> '<div onclick="ToolTipHelp()" style="position: relative" class="div_event">{content}</div>',
						
						'cal_cell_no_content'		=> '{day}',
						'cal_cell_no_content_today'	=> '{day}',
						'cal_cell_blank'			=> '',
						
						'cal_cell_end'				=> '</div></td>',
						
						'cal_cell_end_today'		=> '</td>',
						'cal_row_end'				=> '</tr>',
						'table_close'				=> '</table>',
						'week_num_cell'				=> '<td width="30px" align="center" nowrap><b>{number}</b></td>'
					);	
	}
	
	/**
	 * Set Default Template Data
	 *
	 * This is used in the event that the user has not created their own template
	 *
	 * @access	public
	 * @return array
	 */
	function altern_therapy_template()
	{
		return  array (
						'table_open' 				=> '<table border="0" cellpadding="1" cellspacing="1" width="100%" bgcolor="#d9e4f1">',
						'heading_row_start' 		=> '',
						'heading_previous_cell'		=> '',
						'heading_title_cell' 		=> '',
						'heading_next_cell' 		=> '',
						'heading_row_end' 			=> '',
						'week_row_start' 			=> '',
						'week_day_cell' 			=> '',
						'week_row_end' 				=> '',
						'cal_row_start' 			=> '<tr>',
						'cal_cell_start_empty'		=> '<td valign="top" class="empty_day_cell"><div>',
						
						'cal_cell_start' 			=> '<td class="day_cell"   valign="top">',
						'cal_cell_start_last_30_day'=> '<td class="day_cell last_30_day"   valign="top">',
						'cal_cell_start_today'		=> '<td class="today_cell" valign="top" oonclick="setCheck(\'cb_{day_id}\', !isChecked(\'cb_{day_id}\'))">',
						
						'cal_cell_content'			=> '<div class="div_cell"><div style="float: left; height: 18px"><input type="checkbox" name="cal_day" value="{day_id}" id="cb_{day_id}"></div>' .
													   '<div style="float: right; width: 80%" onclick="setCheck(\'cb_{day_id}\', !isChecked(\'cb_{day_id}\'))"><a onclick="addEvent(\'{day_id}\', this)" style="cursor: pointer">{day}</a></div></div>' .
													   '<div id="cttt_{day_id}" class="div_cttt" onclick="setCheck(\'cb_{day_id}\', !isChecked(\'cb_{day_id}\'))">' .
													   '<div id="up_{day_id}" class="div_up" style="display: none;" onmouseover="changeBackGround(this, \'#edf1f8\')" onmouseout="changeBackGround(this, \'#ffffff\')" onmousedown="scrollDivUp(\'ctt_{day_id}\')"><img src="style/images/blank.gif"></div>' .
													   '<div id="ctt_{day_id}"></div>' .
													   '<div id="dw_{day_id}" class="div_dw" style="display: none;" onmouseover="changeBackGround(this, \'#edf1f8\')" onmouseout="changeBackGround(this, \'#ffffff\')" onmousedown="scrollDivDown(\'ctt_{day_id}\')"><img src="style/images/blank.gif"></div>',
													   
						'cal_cell_content_today'	=> '<a>{day}</a></div><div>{content}',
						
						'content_div'				=> '<div onclick="ToolTipHelp()" style="position: relative" class="div_event">{content}</div>',
						
						//'cal_cell_no_content'		=> '{day}',
						'cal_cell_no_content'		=> '<div class="div_cell"><div style="float: left; height: 18px">&nbsp;</div>{day}</div>', // added by ksa on apr 14, 2019
						'cal_cell_no_content_today'	=> '{day}',
						'cal_cell_blank'			=> '',
						
						'cal_cell_end'				=> '</div></td>',
						
						'cal_cell_end_today'		=> '</td>',
						'cal_row_end'				=> '</tr>',
						'table_close'				=> '</table>',
						'week_num_cell'				=> '<td width="30px" align="center" nowrap><b>{number}</b></td>'
					);	
	}

	// --------------------------------------------------------------------

	/**
	 * Parse Template
	 *
	 * Harvests the data within the template {pseudo-variables}
	 * used to display the calendar
	 *
	 * @access	public
	 * @return	void
	 */
 	function parse_template( $template )
 	{
		$this->temp = $template;
 	
 		if ($this->template == '')
 		{
 			return;
 		}
 		
		$today = array('cal_cell_start_today', 'cal_cell_content_today', 'cal_cell_no_content_today', 'cal_cell_end_today');
		
		foreach (array('table_open', 'table_close', 'heading_row_start', 'heading_previous_cell', 'heading_title_cell', 'heading_next_cell', 'heading_row_end', 'week_row_start', 'week_day_cell', 'week_row_end', 'cal_row_start', 'cal_cell_start', 'cal_cell_start_last_30_day', 'cal_cell_content', 'cal_cell_no_content',  'cal_cell_blank', 'cal_cell_end', 'cal_row_end', 'cal_cell_start_today', 'cal_cell_content_today', 'cal_cell_no_content_today', 'cal_cell_end_today') as $val)
		{
			if (preg_match("/\{".$val."\}(.*?)\{\/".$val."\}/si", $this->template, $match))
			{
				$this->temp[$val] = $match['1'];
			}
			else
			{
				if (in_array($val, $today, TRUE))
				{
					$this->temp[$val] = $this->temp[str_replace('_today', '', $val)];
				}
			}
		} 	
 	}
 	
	function add_days($date, $days) {
	    $timeStamp = $date;
	    $timeStamp+= 24 * 60 * 60 * $days;
	
	    // ...clock change....
	    if (date("I",$timeStamp) != date("I",$date)) {
	        if (date("I",$date)=="1") { 
	            // summer to winter, add an hour
	            $timeStamp+= 60 * 60; 
	        } else {
	            // summer to winter, deduct an hour
	            $timeStamp-= 60 * 60;           
	        } // if
	    } // if
	    $cur_dat = mktime(0, 0, 0, 
	                      date("n", $timeStamp), 
	                      date("j", $timeStamp), 
	                      date("Y", $timeStamp)
	                     ); 
	    return $cur_dat;
	}

}

// END CI_Calendar class
?>
