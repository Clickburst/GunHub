<?php

namespace GunHub\Modules;

use GunHub\Core\Module;

use GunHub\Infrastructure\Listing as ListingPostType;
use GunHub\GunHub;

class Listing {

    use Module;

    public function init() {
        add_action('wp_enqueue_scripts', [$this, 'maybe_load_owl']);
    }

    public function maybe_load_owl() {
        if( is_singular(ListingPostType::SLUG ) ) {
            wp_enqueue_script( 'blueimp-gallery', GunHub::get_instance()->plugin_url . 'js/blueimp-gallery/js/blueimp-gallery.min.js', ['jquery'], null, true );
            wp_enqueue_style( 'blueimp-gallery', GunHub::get_instance()->plugin_url . 'js/blueimp-gallery/css/blueimp-gallery.min.css' );
            
            wp_enqueue_script( 'gunhub-front-main', GunHub::get_instance()->plugin_url . 'js/front-main.js', ['jquery'], null, true );
        }
    }

}
