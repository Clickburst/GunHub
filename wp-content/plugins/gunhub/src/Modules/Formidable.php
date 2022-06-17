<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\Settings;
use GunHub\Data\Shop;

class Formidable {

    use Module {
        Module::__construct as private __ModuleConstruct;
    }
    
    private static $listing_id_field_name = 'gh-listing-id';
    
    private $settings;
    
    // todo - make it dynamic with ACF settings page
    private static $form_id = '2';

    public function __construct()  {
        $this->__ModuleConstruct();
        
        $this->settings = new Settings( false );
    }
    
    public function init() {
        add_action('gunhub_single_listing_contact_seller_form', [$this, 'print_contact_seller_form'] );
        add_shortcode('gunhub_current_user_data_in_seller_form', [$this, 'current_user_data_in_seller_form']);
        add_filter('frm_to_email', [$this, 'add_seller_email_to_cc'], 10, 3);
        add_filter('frm_email_message', [$this, 'update_email_message']);
        add_action('frm_entry_form', [$this, 'print_hidden_fields']);
    }

    public function update_email_message( $message ) {

        $current_user_data = $this->get_current_user_contact_data();
        
        if( empty( $current_user_data ) ) {
            return $message;
        }

        foreach ( $current_user_data as $key => $val ) {
            $message = str_replace('{'. $key . '}', $val, $message);
        }
        
        return $message;
    }

    public function current_user_data_in_seller_form() {
        $current_user_data = $this->get_current_user_contact_data();
        if( empty( $current_user_data ) ) {
            return '';
        }
        ob_start();
        $this->print_field_in_wrapper('Email', $current_user_data['email']);
        $this->print_field_in_wrapper('First Name', $current_user_data['first_name']);
        $this->print_field_in_wrapper('Last Name', $current_user_data['last_name']);
        
        $this->print_edit_account_details_message();
        return ob_get_clean();
    }

    private function print_edit_account_details_message() {
        
        if( $url = Shop::get_my_account_url('edit-account') ) {
            printf('%s <a href="'. esc_url( $url ) .'">%s</a>',
                __( 'Profile deatails can be edited', 'gunhub' ),
            __('here', 'gunhub'));
        } else {
            _e('profile details can be edited in my-account', 'gunhub');
        }
    }
    
    public function add_seller_email_to_cc( $to, $values, $form_id ) {
        
        if( $form_id !== $this->settings->contact_seller_form_id() ) {
            return $to;
        }
        
        if( ! isset( $_POST[self::$listing_id_field_name] ) ) {
            return $to;
        }
        
        $listing_id = intval( $_POST[self::$listing_id_field_name] );
        if( 0 === $listing_id ) {
            return $to;
        }
        
        $listing = get_post( $listing_id );
        
        if( ! isset( $listing->post_author ) ) {
            return $to;
        }

        $seller_data = get_userdata($listing->post_author);
        
        if( isset( $seller_data->user_email ) ) {
            $to[] = $seller_data->user_email;
        }
        
        return $to;
    }

    public function print_hidden_fields( $form ) {
        if( $form->id !== self::$form_id ) {
            return '';
        }
        printf('<input type="hidden" name="%s" value="%d">', self::$listing_id_field_name, get_the_id());
    }

    private function print_field_in_wrapper( $label, $val ) { ?>
        <div class="form-field">
            <label class="frm_primary_label"><?php echo esc_html( $label ); ?></label>
            <input type="text" value="<?php echo esc_attr( $val ); ?>" disabled="disabled">
        </div>
        <?php
    }

    public function print_contact_seller_form() {
        echo do_shortcode('[formidable id=contactgunseller user_id=current]');
    }


    private function get_current_user_contact_data() {
        $user = wp_get_current_user();
        if( 0 === $user->ID) {
            return [];
        }
        
        return [
            'email' => $user->user_email,
            'first_name' => get_user_meta( $user->ID, 'first_name', true ),
            'last_name' => get_user_meta( $user->ID, 'last_name', true )
        ];
    }
}
