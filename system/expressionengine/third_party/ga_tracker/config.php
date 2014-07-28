<?php

if (!defined('GA_TRACKER')) {
    define('GA_TRACKER_NAME', 'GA Tracker');
    define('GA_TRACKER_CLASS', 'Ga_tracker');
    define('GA_TRACKER_VERSION', '0.0.1');
}

$config['name']        = GA_TRACKER_NAME;
$config['version']     = GA_TRACKER_VERSION;
$config['description'] = "Simple Server Side Analytics implementation for Expression Engine";
