<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\ListingFrontendVariables;
use GunHub\Data\Order;
use GunHub\Data\Seller;
use GunHub\Data\Shop;
use GunHub\Infrastructure\SellerACF;

class Woocommerce {

    use Module {
        Module::__construct as private __ModuleConstruct;
    }
    
    private static $listing_id_field_name = 'gh-listing-id';

    public function __construct()  {
        $this->__ModuleConstruct();
    }


    public function init() {
        add_action('init', [$this, 'register_my_account_seller_endpoint']);
        add_filter('query_vars', [$this, 'register_my_account_seller_query_var']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_my_account_listing_menu_item']);
        add_action('woocommerce_account_seller_endpoint', [$this, 'my_account_seller_details_endpoint']);
        add_action('woocommerce_account_' . ListingFrontendVariables::$my_listings_url . '_endpoint', [$this, 'my_account_my_listings_endpoint']);
        add_action('woocommerce_account_' . ListingFrontendVariables::$new_listing_url . '_endpoint', [$this, 'my_account_new_listing_endpoint']);
        
        add_action('woocommerce_payment_complete', [$this, 'maybe_add_user_credits_on_complete_order']);
        
        add_action('gunhub_woocommerce_edit_account_after_email_address', [$this, 'print_my_account_acf_form']);
        
//        add_action('wp_head', [$this, 'wp_head']);
    }

//    public function wp_head() {
//        $seller_id = get_current_user_id();
//        var_dump($seller_id);
//        $seller = new Seller( $seller_id );
//        var_dump($seller->is_seller_or_admin());
//        die;
//    }


    public function register_my_account_seller_endpoint() {
        add_rewrite_endpoint( 'seller', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( ListingFrontendVariables::$my_listings_url, EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( ListingFrontendVariables::$new_listing_url, EP_ROOT | EP_PAGES );
    }

    public function register_my_account_seller_query_var( $vars ) {
        $vars[] = 'seller';
        $seller = new Seller(get_current_user_id());
        if( $seller->is_seller_or_admin() ) {
            $vars[] = ListingFrontendVariables::$my_listings_url;
            $vars[] = ListingFrontendVariables::$new_listing_url;
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
        if( $seller->is_seller_or_admin() ) {
            $items[ListingFrontendVariables::$my_listings_url] = 'My Listings';
            $items[ListingFrontendVariables::$new_listing_url] = 'New Listing';
        }

        if( $backup ) {
            $items['customer-logout'] = $backup;
        }
        return $items;
    }

    public function my_account_seller_details_endpoint() {
        $seller = new Seller( get_current_user_id() );
        if( ! $seller->is_seller_or_admin() ) {
            // todo - dynamic shop page url
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
        
        if( ! $seller->is_seller_or_admin() ) {
            echo 'Please contact site admin, you need to have "seller" role to create ads';
            return;
        }
        
        if( 0 === $seller->get_credits() ) {
            echo 'You have no credits to post listing';
        } else {
            ListingFrontendBuilder::print_add_listing_form();
        }
    }

    public function my_account_my_listings_endpoint() {
        if( isset( $_GET[ListingFrontendVariables::$listgin_id_url] ) ) {
            $listing_id = (int) $_GET[ListingFrontendVariables::$listgin_id_url];
            $listing = new \GunHub\Data\Listing( $listing_id );
            
            if( $listing->belongs_to_current_user() ) {
                if( ! $listing->is_editable() ) {
                    $this->print_not_allowed_to_edit();
                    return;
                }
                $this->print_seller_edit_listing( $listing_id );
                return;
            }
        }
        $this->print_seller_listings();
    }
    
    private function print_not_allowed_to_edit() {
        $my_listings_url = Shop::get_my_listings_url();
        echo 'Sorry, you are not allowed to edit published listings ';
        if( '' !== $my_listings_url ) {
            printf('<a href="%s">%s</a', $my_listings_url, 'go back');
        }
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

        $seller_id = $order->get_user_id();
        if( $seller_id ) {
            $seller = new Seller( $seller_id );
            if( ! $seller->is_seller_or_admin() ) {
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
