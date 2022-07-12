<?php
namespace GunHub\Modules;

use GunHub\Core\Module;

class HeaderButtonShortcode {

    use Module;

    public function init() {
        add_shortcode('gunhub_add_listing_header_button', [$this, 'register_my_account_seller_endpoint']);
    }

    public function register_my_account_seller_endpoint(): string {
        
        if( ! is_user_logged_in() ) {
            return '';
        }
        
        // todo - create dynamic setting for button url
        ob_start();?>
        <a href="/signup" class="gunhub-hero-add-listing-button"><?php _e('Place An Ad', 'gunhub'); ?></a>
        <?php
        return ob_get_clean();
    }
}
