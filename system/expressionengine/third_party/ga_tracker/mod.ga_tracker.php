<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * GA Server Side Tracker
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Diogo Silva
 * @copyright	Copyright (c) 2014 Twentyfoursquare
 * @link		http://24sq.com
 */

class Ga_tracker {

	private $EE;

	private static $_ga_host;
	private static $_ga_key;
	
	public $error_msgs;
	
	function __construct() {
		$this->EE =& get_instance(); // Make a local reference to the ExpressionEngine super object
		$this->EE->load->library('ga_tracker_lib'); // load SSGA library

		// Get Host dynamically
		self::$_ga_host = $_SERVER['HTTP_HOST'];

		// Get GA keys from settings row
		if (!isset(self::$_ga_key)) {
			
			$query = $this->EE->db->select('google_analytics_key')
				                       ->where('site_id', $this->EE->config->item('site_id'))
				                       ->get('gatracker_config');

			$settings = $query->row('google_analytics_key');
			self::$_ga_key = $settings;
		}
	}
 
	/**
     * Get link to record a page view
	 */	
	function pageview_link() {	
		
		// Fetch template params
		$title 	= urlencode($this->EE->TMPL->fetch_param('title'));
		$page 	= $this->EE->TMPL->fetch_param('page') ? urlencode($this->EE->TMPL->fetch_param('page')) : urlencode($this->EE->uri->uri_string);
		$rurl 	= $this->EE->TMPL->fetch_param('rurl') ? urlencode($this->EE->TMPL->fetch_param('rurl')) : urlencode($this->EE->uri->uri_string);

		// Form pageview track link url
		return $this->EE->functions->fetch_site_index().QUERY_MARKER.
		'ACT='.$this->EE->functions->fetch_action_id('Ga_tracker', 'track_pageview').AMP.
		'page='.$page.AMP.'title='.$title.AMP.'rurl='.$rurl;
	}

	/**
     * Get link to record a GA event
	 */	
	function event_link() {	
		
		// Fetch template params
		$event 		= urlencode($this->EE->TMPL->fetch_param('event'));
		$category 	= urlencode($this->EE->TMPL->fetch_param('category'));
		$label    	= $this->EE->TMPL->fetch_param('label') ? urlencode($this->EE->TMPL->fetch_param('label')) : "";
		$rurl 		= $this->EE->TMPL->fetch_param('rurl') ? urlencode($this->EE->TMPL->fetch_param('rurl')) : urlencode($this->EE->uri->uri_string);

		// Form event track link url
		return $this->EE->functions->fetch_site_index().QUERY_MARKER.
		'ACT='.$this->EE->functions->fetch_action_id('Ga_tracker', 'track_event').AMP.
		'event='.$event.AMP.'category='.$category.AMP.'label='.$label.AMP.'rurl='.$rurl;
	}

	/**
     * Track pageview server-side
	 */	
	function track_pageview() {

		// Gets variables
		$page    		= urldecode($this->EE->input->get('page'));
		$title 			= urldecode($this->EE->input->get('title'));
		$redirect_url 	= urldecode($this->EE->input->get('rurl'));



		if ($redirect_url) {
			$redirect_url = $this->EE->functions->create_url($redirect_url, TRUE);
		}

		// Create SSGA class and set pageview
		$ssga = new Ga_tracker_lib(self::$_ga_key, self::$_ga_host);
		$ssga->set_page($page);
		$ssga->set_page_title($title);
		$ssga->send();

		// Redirect
		header("Location: " . $redirect_url);
		die();
		
	}

	/**
     * Track events server-side
	 */	
	function track_event() {

		// Gets variables
		$event    		= urldecode($this->EE->input->get('event'));
		$category 		= urldecode($this->EE->input->get('category'));
		$label    		= urldecode($this->EE->input->get('label'));
		$redirect_url 	= urldecode($this->EE->input->get('rurl'));



		if ($redirect_url) {
			$redirect_url = $this->EE->functions->create_url($redirect_url, TRUE);
		}

		// Create SSGA class and send event
		$ssga = new Ga_tracker_lib(self::$_ga_key, self::$_ga_host);
		$ssga->set_event($event, $category, $label, 1);
		$ssga->send();


		// Redirect
		header("Location: " . $redirect_url);
		die();
		
	}
	
	
}

/* End of file mod.ga_tracker.php */ 
/* Location: ./system/expressionengine/third_party/ga_tracker/mod.ga_tracker.php */ 