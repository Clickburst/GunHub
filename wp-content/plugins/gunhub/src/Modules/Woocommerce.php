<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\ListingFrontendVariables;
use GunHub\Data\Order;
use GunHub\Data\Seller;
use GunHub\Data\Shop;
use GunHub\Infrastructure\SellerACF;


/**
 * todo - style acf form on checkout
 */
class Woocommerce {

    use Module {
        Module::__construct as private __ModuleConstruct;
    }
    
//    private static $listing_id_field_name = 'gh-listing-id';
    private const THANK_YOU_PAGE_COUNTER_REDIRECT = 4;

    public function __construct()  {
        $this->__ModuleConstruct();
    }


    public function init() {
        add_action('init', [$this, 'register_my_account_seller_endpoint']);
        add_filter('query_vars', [$this, 'register_my_account_seller_query_var']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_my_account_listing_menu_item']);
        add_action('woocommerce_account_seller_endpoint', [$this, 'my_account_seller_details_endpoint']);
        add_action('woocommerce_account_' . ListingFrontendVariables::MY_LISTINGS_URL . '_endpoint', [$this, 'my_account_my_listings_endpoint']);
        add_action('woocommerce_account_' . ListingFrontendVariables::NEW_LISTING_URL . '_endpoint', [$this, 'my_account_new_listing_endpoint']);
        
        add_action('woocommerce_payment_complete', [$this, 'maybe_add_user_credits_on_complete_order']);
        
        add_action('gunhub_woocommerce_edit_account_after_email_address', [$this, 'print_my_account_acf_form']);
        
        add_filter('woocommerce_product_get_category_ids', [$this, 'hide_uncategorized_category_single_product']);

        // todo - do we need order additional field?
//        add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


        add_action('woocommerce_after_checkout_billing_form', [$this, 'print_seller_form_on_checkout_page']);
        add_action('woocommerce_after_checkout_validation', [$this, 'seller_acf_form_validation_checkout_page'], 10, 2);
        add_action('user_register', [$this, 'save_seller_acf_data_from_checkout_page']);
        add_action('woocommerce_before_thankyou', [$this, 'print_redirect_js']);
        
        add_action('woocommerce_email_additional_content_customer_new_account', [$this, 'print_redirect_js']);
        
        add_action('woocommerce_email_order_details', [$this, 'print_add_listing_page_link_processing_order_email'], 10, 4);
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
        add_rewrite_endpoint( ListingFrontendVariables::MY_LISTINGS_URL, EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( ListingFrontendVariables::NEW_LISTING_URL, EP_ROOT | EP_PAGES );
    }

    public function register_my_account_seller_query_var( $vars ) {
        $vars[] = 'seller';
        $seller = new Seller(get_current_user_id());
        if( $seller->is_seller_or_admin() ) {
            $vars[] = ListingFrontendVariables::MY_LISTINGS_URL;
            $vars[] = ListingFrontendVariables::NEW_LISTING_URL;
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
            $items[ListingFrontendVariables::MY_LISTINGS_URL] = 'My Listings';
            $items[ListingFrontendVariables::NEW_LISTING_URL] = 'New Listing';
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
        
        $credits_message = $seller->get_credits_left_message();
        ?>
        <h3><?php echo $credits_message; ?></h3>
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
            echo 'You have no credits to create new listing';
        } else {
            ListingFrontendBuilder::print_add_listing_form();
        }
    }

    public function my_account_my_listings_endpoint() {
        if( isset( $_GET[ListingFrontendVariables::LISTING_ID_URL] ) ) {
            $listing_id = (int) $_GET[ListingFrontendVariables::LISTING_ID_URL];
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
        self::print_seller_details_form();
    }

    /**
     * todo - maybe add check if its listing credit product, currently listing credit is the only product we have
     * @return void
     */
    public function print_seller_form_on_checkout_page() {
        if( 
            self::is_one_page_checkout()
            && ! is_user_logged_in()
        ) {
            self::print_seller_details_form(true);
            self::print_acf_fix_browser_reload_alert();
        }
    }

    private static function print_seller_details_form( $on_checkout = false ) {
        if( ! function_exists('acf_form') ) {
            return;
        }
        $args = [
            'post_id' => 'user_' . get_current_user_id(),
            'fields' => [
                SellerACF::LICENCE_ID,
            ],
            'return' => false,
            'submit_value' => __('Save Changes', 'aspirantus')
        ];
        
        if( $on_checkout ) {
            $args['form'] = false;
        }
        
        acf_form_head();
        acf_form($args);
    }

    /**
     * Fix for woocommerce checkout page. 
     * When woocommerce tries to reload the page after successful ajax call - alert 'Changes that you made may not be saved.' appears
     * @return void
     */
    private function print_acf_fix_browser_reload_alert() {
        ?>
        <script>
          (function($) {
            $(document).ready(function() {
              if( acf !== undefined ) {
                acf.unload.active = false
              }
            });
          })(jQuery);
        </script>
        <?php
    }

    public function hide_uncategorized_category_single_product( $category_ids ) {
        if( ! is_product() ) {
            return $category_ids;
        }

        foreach ( $category_ids as $key => $category_id ) {
            if( $category_id === (int) get_option( 'default_product_cat' ) ) {
                unset( $category_ids[$key] );
            }
        }
        
        return $category_ids;
    }
    
    public static function is_one_page_checkout() {
        return is_product() && is_checkout();
    }

    public function seller_acf_form_validation_checkout_page($data, $errors) {
        if( is_user_logged_in() ) {
            return;
        }

        // Bail early if $_POST['acf'] doesn't exists
        if ( !acf_maybe_get_POST( 'acf' ) ) {
            return;
        }

        acf_setup_meta($_POST['acf'], 'form_validation', true);

        if( ! get_field(SellerACF::LICENCE_ID) ) {
            $errors->add( 'seller_phone', __( '<strong>Missing</strong> seller licence id.', 'woocommerce' ) );
        }

        acf_reset_meta('form_validation');
    }

    public function print_redirect_js( $order_id ) {
        $order = wc_get_order( $order_id );
        if (
            ! $order
            || $order->has_status( 'failed' ) 
        ) {
            return;
        }
        
        $new_listing_url = Shop::get_new_listing_url();
        if( $new_listing_url === '' ) {
            return;
        }
        ?>
        <h3 class="text-center brand-color underline"><a href="<?php echo esc_url($new_listing_url); ?>">You will be redirected to create listing page in <span gunhub-counter><?php echo self::THANK_YOU_PAGE_COUNTER_REDIRECT + 1; ?></span>s</a> <button gunub-stop-redirect-counter class="gunhub-stop-counter-thank-you-page"><?php _e('Stop', 'gunhub'); ?></button></h3>
        
        <script>

          (function($) {
            $(document).ready(function() {
              let timeLeft = <?php echo self::THANK_YOU_PAGE_COUNTER_REDIRECT; ?>;
              const downloadTimer = setInterval(function(){
                if(timeLeft <= 0){
                  window.location.replace("<?php echo esc_url( $new_listing_url ); ?>");
                  clearInterval(downloadTimer);
                }

                $('[gunhub-counter]').text(timeLeft)
                timeLeft -= 1;
              }, 1000);

              $('[gunub-stop-redirect-counter]').click(function (e){
                  e.preventDefault();
                clearInterval(downloadTimer);
              })

            });
          })(jQuery)
          
          
        </script>
        <?php
    }

    // todo - new order doesn't create account now - make it create account, test acf save field, polish acf validation
    public function save_seller_acf_data_from_checkout_page( $user_id ) {
        $licence_id = $_POST['acf'][SellerACF::LICENCE_ID_F_ID];
        update_field(SellerACF::LICENCE_ID_F_ID, $licence_id, 'user_' . $user_id);
    }

    public function print_add_listing_page_link_processing_order_email($order, $sent_to_admin, $plain_text, $email) {
        if( $email->id === 'customer_processing_order' ) {
            $new_listing_url = Shop::get_new_listing_url();
            if( ! $new_listing_url ) {
                return '';
            }
            printf('<p><a href="%s" target="_blank">%s</a></p>', $new_listing_url, __('Create New Listing', 'gunhub'));
        }
    }
}
