<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\Order;
use GunHub\Data\Seller;
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
        add_action('woocommerce_account_seller_endpoint', [$this, 'my_account_listing_content']);
        
        add_action('woocommerce_payment_complete', [$this, 'maybe_add_user_credits_on_complete_order']);
        
        add_action('gunhub_woocommerce_edit_account_after_email_address', [$this, 'print_my_account_acf_form']);
    }

    public function register_my_account_seller_endpoint() {
        add_rewrite_endpoint( 'seller', EP_ROOT | EP_PAGES );
    }

    public function register_my_account_seller_query_var( $vars ) {
        $vars[] = 'seller';
        return $vars;
    }

    public function add_my_account_listing_menu_item( $items ) {

        $backup = false;
        if( isset( $items['customer-logout'] ) ) {
            $backup = $items['customer-logout'];
            unset( $items['customer-logout'] );
        }
        $items['seller'] = 'Seller details';

        if( $backup ) {
            $items['customer-logout'] = $backup;
        }
        return $items;
    }

    public function my_account_listing_content() {
        $seller = new Seller( get_current_user_id() );
        $credits = $seller->get_credits();
        ?>
        <h3><?php printf( 'You have %s left', sprintf( _n( '%s credit', '%s credits', $credits, 'gunhub' ), $credits ) ) ?></h3>
        <?php
        $this->print_my_account_acf_form();
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
