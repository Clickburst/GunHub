<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\Order;
use GunHub\Data\Seller;
use GunHub\GunHub;
use GunHub\Infrastructure\SellerACF;

class Woocommerce {

    use Module {
        Module::__construct as private __ModuleConstruct;
    }
    
    private static $listing_id_field_name = 'gh-listing-id';

    public function __construct()  {
        $this->__ModuleConstruct();
        
//        $this->settings = new Settings( false );
    }


    public function init() {
        add_action('init', [$this, 'register_my_account_seller_endpoint']);
        add_filter('query_vars', [$this, 'register_my_account_seller_query_var']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_my_account_listing_menu_item']);
        add_action('woocommerce_account_seller_endpoint', [$this, 'my_account_seller_details_endpoint']);
        add_action('woocommerce_account_my-listings_endpoint', [$this, 'my_account_my_listings_endpoint']);
        add_action('woocommerce_account_new-listing_endpoint', [$this, 'my_account_new_listing_endpoint']);
        
        add_action('woocommerce_payment_complete', [$this, 'maybe_add_user_credits_on_complete_order']);
        
        add_action('gunhub_woocommerce_edit_account_after_email_address', [$this, 'print_my_account_acf_form']);
    }

    public function register_my_account_seller_endpoint() {
        add_rewrite_endpoint( 'seller', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'my-listings', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'new-listing', EP_ROOT | EP_PAGES );
    }

    public function register_my_account_seller_query_var( $vars ) {
        $vars[] = 'seller';
        $seller = new Seller(get_current_user_id());
        if( $seller->is_seller() ) {
            $vars[] = 'my-listings';
            $vars[] = 'new-listing';
        }
        return $vars;
    }

    public function add_my_account_listing_menu_item( $items ) {

        $backup = false;
        if( isset( $items['customer-logout'] ) ) {
            $backup = $items['customer-logout'];
            unset( $items['customer-logout'] );
        }
        $items['seller'] = 'Seller details';
        
        $seller = new Seller(get_current_user_id());
        if( $seller->is_seller() ) {
            $items['my-listings'] = 'My Listings';
            $items['new-listing'] = 'New Listing';
        }

        if( $backup ) {
            $items['customer-logout'] = $backup;
        }
        return $items;
    }

    public function my_account_seller_details_endpoint() {
        $seller = new Seller( get_current_user_id() );
        if( ! $seller->is_seller() ) {
            ?>
            <h3>If you want to post some listings - please purchase credits <a href="/shop">here</a></h3>
            <p><?php printf('Listing is displayed for %d days', Listing::$expired_days); ?></p>
            <?php
            return;
        }
        
        $credits = $seller->get_credits();
        ?>
        <h3><?php printf( 'You have %s left', sprintf( _n( '%s credit', '%s credits', $credits, 'gunhub' ), $credits ) ) ?></h3>
        <?php
        $this->print_my_account_acf_form();
    }

    public function my_account_new_listing_endpoint() {
        if( ! function_exists('acf_form') ) {
            return;
        }

        $seller = new Seller(get_current_user_id());
        
        if( ! $seller->is_seller() ) {
            return;
        }
        ListingFrontendBuilder::print_add_listing_form();

    }

    public function my_account_my_listings_endpoint() {
        if( isset( $_GET['listing'] ) ) {
            $listing_id = (int) $_GET['listing'];
            if( get_current_user_ID() === (int) get_post_field ('post_author', $listing_id) ) {
                $this->print_seller_edit_listing( $listing_id );
                return;
            }
        }
        $this->print_seller_listings();
    }

    private function print_seller_edit_listing( $listing_Id ) {
        ListingFrontendBuilder::print_edit_listing_form( $listing_Id );
    }

    private function print_seller_listings() {
        ListingFrontendBuilder::print_seller_listings();
    }

    public function maybe_add_user_credits_on_complete_order( $order_id ) {
        $order = new Order($order_id);
        $order_credits = $order->get_credits();
        
        if( ! $order_credits ) {
            return;
        }

        $seller = $order->get_user_id();
        if( $seller ) {
            $seller = new Seller( $order->get_user_id() );
            if( ! $seller->is_seller() ) {
                $seller->update_to_seller();
            }
            $seller->add_credits( $order_credits );
        }
    }

    public function print_my_account_acf_form() {
        if( ! function_exists('acf_form') ) {
            return;
        }
        acf_form_head();
        acf_form([
            'post_id' => 'user_' . get_current_user_id(), 
            'fields' => [
                SellerACF::$phone_number,
                SellerACF::$licence_id,
                SellerACF::$location,
                SellerACF::$type,
            ],
            'return' => false,
            'submit_value' => __('Save Changes', 'aspirantus')
        ]);
    }
}
