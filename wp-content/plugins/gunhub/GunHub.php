<?php
/*
Plugin Name: GunHub 
Plugin URI: https://jointheteem.com/ 
Description: GunHub custom functions
Version: 1.0.0
Author: Artem Lapkin
Author URI: https://automattic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: jointheteem
*/

namespace GunHub;

use GunHub\Core\Module;
use GunHub\Modules\ModulesProvider;


require_once __DIR__ . '/vendor/autoload.php';


class GunHub {

    use Module;

    /** @var string */
    public $plugin_path;
    
    /** @var string */
    public $plugin_url;

    /**
     * @return void
     */
    public function init() {

        $this->plugin_path = __DIR__;
        
        $this->plugin_url = plugin_dir_url(__FILE__);

        ModulesProvider::get_instance();
        Infrastructure\InfrastructureProvider::get_instance();

    }
}

/**
 * @return mixed
 */
function gunhub() {
    return GunHub::get_instance();
}

// Init plugin
gunhub();