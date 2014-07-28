<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * GA Server Side Tracker
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Diogo Silva
 * @copyright	Copyright (c) 2014 Diogo Silva
 * @link		https://github.com/diogomiguel/ee_ga_tracker
 */

require_once PATH_THIRD . 'ga_tracker/config.php';

class Ga_tracker_upd
{
    public $name = GA_TRACKER_NAME;
    public $version = GA_TRACKER_VERSION;
    
    function __construct()
    {
        $this->EE =& get_instance();
    }
    
    function install()
    {
        
        // install module 
        $this->EE->db->insert('modules', array(
            'module_name' => 'Ga_tracker',
            'module_version' => $this->version,
            'has_cp_backend' => 'y',
            'has_publish_fields' => 'n'
        ));
        
        // Register form actions
        
        // Track events action
        $this->EE->db->insert('actions', array(
            'class' => 'Ga_tracker',
            'method' => 'track_event'
        ));
        
        // Track pages action
        $this->EE->db->insert('actions', array(
            'class' => 'Ga_tracker',
            'method' => 'track_pageview'
        ));
        
        
        // Insert settings
        $this->EE->load->dbforge();
        $gatracker_config_fields = array(
            'gatracker_config_id' => array(
                'type' => 'int',
                'constraint' => '10',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'site_id' => array(
                'type' => 'int',
                'constraint' => '10',
                'unsigned' => TRUE
            ),
            
            'google_analytics_key' => array(
                'type' => 'varchar',
                'constraint' => '60',
                'null' => FALSE
            )
            
        );
        
        $this->EE->dbforge->add_field($gatracker_config_fields);
        $this->EE->dbforge->add_key('gatracker_config_id', TRUE);
        $this->EE->dbforge->create_table('gatracker_config');
        return TRUE;
    }
    
    
    
    function update($current = '')
    {
        if (empty($current))
            return FALSE;
        
        
        if ($current < $this->version)
            return TRUE;
        else
            return FALSE;
    }
    
    function uninstall()
    {
        $this->EE->load->dbforge();
        
        // Delete from module table
        $this->EE->db->where('module_name', "Ga_tracker");
        $this->EE->db->delete('modules');
        
        // Delete custom actions
        $this->EE->db->where('class', 'Ga_tracker');
        $this->EE->db->delete('actions');
        $this->EE->dbforge->drop_table('gatracker_config');
        
        return TRUE;
    }
}

/* End of file upd.ga_tracker.php */
/* Location: ./system/expressionengine/third_party/ga_tracker/upd.ga_tracker.php */