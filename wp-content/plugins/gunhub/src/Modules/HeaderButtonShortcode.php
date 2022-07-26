<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\Settings;
use GunHub\Data\Shop;

class HeaderButtonShortcode {

    use Module;

    public function init() {
        add_shortcode('gunhub_add_listing_header_button', [$this, 'register_my_account_seller_endpoint']);
    }

    public function register_my_account_seller_endpoint(): string {
        
        $url = $this->get_button_url();
        
        // todo - create dynamic setting for button url
        ob_start();?>
        <a href="<?php echo esc_html( $url ); ?>" class="gunhub-hero-add-listing-button"><?php _e('Place An Ad', 'gunhub'); ?></a>
        <?php
        return ob_get_clean();
    }

    private function get_button_url() {
        $out = Shop::get_new_listing_url();

        if( ! is_user_logged_in() ) {
            $setting = new Settings( false );

            $page_id = $setting->get_place_an_ad_guest_page();
            
            $out = $page_id 
                ? get_permalink( $page_id )
                : '/signup';
        }
        return $out;
        // todo - navigate users to purchase credit page if he is not 'seller'?
//        $seller = new Seller($user_id);
//        if( ! $seller->is_seller_or_admin() ) {
//            
//        }
    }
}
