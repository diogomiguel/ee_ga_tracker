<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * GA Server Side Tracker
 *
 * @package     ExpressionEngine
 * @subpackage  Addons
 * @category    Modules
 * @author      Diogo Silva
 * @copyright   Copyright (c) 2014 Twentyfoursquare
 * @link        http://24sq.com
 */
class Ga_tracker_mcp {
    var $base;          // the base url for this module         
    var $form_base;     // base url for forms
    var $module_name = "ga_tracker";  

    function Ga_tracker_mcp($switch = TRUE) {
        // Make a local reference to the ExpressionEngine super object
        $this->EE =& get_instance(); 
        $this->base      = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
        $this->form_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;

        // Load what's needed
        $this->EE->load->library('table');
        $this->EE->load->library('javascript');
        $this->EE->load->helper('form');
        $this->EE->lang->loadfile('ga_tracker');
    }

    function index()  {
        $vars = array();

        // Get Config for the current site
        $site_id = $this->EE->config->item('site_id');
        $config = $this->EE->db->get_where('gatracker_config', array('site_id' => $site_id));

        if($config->num_rows() == 0) // we did not find any config for this site id, so just load any other
        {
            $config = $this->EE->db->get_where('gatracker_config');
        }
        $vars['google_analytics_key'] = $config->row('google_analytics_key')? $config->row('google_analytics_key') : '';

        return $this->content_wrapper('index', 'ga_tracker_settings', $vars);
    }
    
    function save_settings() {
        // Save Analytics Key in the database

        $google_analytics_key = $this->EE->input->post('google_analytics_key');

        $site_id = $this->EE->config->item('site_id');
        $config = $this->EE->db->get_where('gatracker_config', array('site_id' => $site_id));

        $data_arr = array(
            'google_analytics_key' => $google_analytics_key,
        );

        if($config->num_rows() == 0) {
            $data_arr['site_id'] = $site_id;
            $this->EE->db->insert('gatracker_config', $data_arr);
        } else {
            $this->EE->db->where('site_id', $site_id);
            $this->EE->db->update('gatracker_config', $data_arr);
        }

        $this->EE->session->set_flashdata('message_success', lang('settings_saved'));
        $this->EE->functions->redirect($this->base);
    }

    
    function content_wrapper($content_view, $lang_key, $vars = array()) {
        
        $vars['content_view'] = $content_view;
        $vars['_base'] = $this->base;
        $vars['_form_base'] = $this->form_base;

        // $this->EE->cp->set_variable was deprecated in 2.6
        if (version_compare(APP_VER, '2.6', '>=')) {
            $this->EE->view->cp_page_title = lang($lang_key);
        } else {
            $this->EE->cp->set_variable('cp_page_title', lang($lang_key));
        }

        $this->EE->cp->set_breadcrumb($this->base, lang('ga_tracker_module_name'));
        return $this->EE->load->view('_wrapper', $vars, TRUE);
    }
    
}

/* End of file mcp.ga_tracker.php */ 
/* Location: ./system/expressionengine/third_party/ga_tracker/mcp.ga_tracker.php */ 