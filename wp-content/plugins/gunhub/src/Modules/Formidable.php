<?php
namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\Settings;
use GunHub\Data\Shop;
use GunHub\Infrastructure\Listing as ListingPostType;

class Formidable {

    use Module {
        Module::__construct as private __ModuleConstruct;
    }
    
    private static $listing_id_field_name = 'gh-listing-id';
    private static $name_field_name = 'gh-name';
    private static $email_field_name = 'gh-email';
    private static $message_field_name = 'gh-message';

    /**
     * must be used as a hidden field in report abuse and contact seller forms.
     * Used for storing custom meta
     * 
     * @var string 
     */
    private static $hidden_meta_field = 'gh-meta';
    
    private $settings;

    public function __construct()  {
        $this->__ModuleConstruct();
        
        $this->settings = new Settings();
    }
    
    public function init() {
        add_shortcode('gunhub_current_user_data_in_seller_form', [$this, 'contact_seller_dynamic_fields']);
        add_shortcode('gunhub_current_user_data_in_report_abuse_form', [$this, 'report_abuse_dynamic_fields']);
        
        add_action('gunhub_single_listing_contact_seller_form', [$this, 'print_contact_seller_form'] );
        add_filter('frm_to_email', [$this, 'add_seller_email_to_cc'], 10, 3);
        add_filter('frm_email_message', [$this, 'update_form_message'], 10, 2);
        add_action('frm_entry_form', [$this, 'print_hidden_fields']);

        add_action('frm_show_entry', [$this, 'print_additional_info_backend_entry_page']);

        add_action('frm_after_create_entry', [$this, 'after_create_entry'], 10, 2);
    }

    public function print_additional_info_backend_entry_page( $entry ) {
        if( ! class_exists( '\FrmEntryMeta' ) ) {
            return;
        }

        $meta_field_id = self::get_meta_field_id_for_form( $entry->form_id );

        if( is_null( $meta_field_id ) ) {
            return;
        }
        $custom_meta = \FrmEntryMeta::get_entry_meta_by_field($entry->id, $meta_field_id );
        
        if( is_null( $custom_meta ) ) {
            return;
        }

        $this->print_custom_meta_in_table( $custom_meta );
    }

    private function print_custom_meta_in_table( $custom_meta ) {
     ?>
        <table cellspacing="0" class="frm-alt-table">
            <tbody>
                <?php foreach ( $custom_meta as $key => $value ) {
                    ?>
                    <tr>
                        <th><?php echo esc_html( ucwords( str_replace('_', ' ', $key) ) ); ?></th>
                        <td><?php echo esc_html( $value ); ?></td>
                    </tr>
                    <?php
                } ?>
            </tbody>
        </table>
     <?php   
    }

    public function update_form_message( $message, $data ) {
        $message = $this->fill_email_body_with_user_contact_data( $message, $data );
        return $this->fill_email_body_with_listing_data( $message, $data );
    }

    private function fill_email_body_with_user_contact_data( $message, $data ) {

        $user_data = $this->get_current_user_contact_data();

        if( ! is_user_logged_in() 
            && (int) $data['form']->id === $this->settings->report_abuse_form_id() ) {
            $user_data = $this->get_guest_user_contact_data();
        }
        
        if( empty( $user_data ) ) {
            return $message;
        }

        foreach ( $user_data as $key => $val ) {
            $message = str_replace('{'. $key . '}', $val, $message);
        }
        return $message;
    }

    private function fill_email_body_with_listing_data( $message, $data ) {
        $replace = [];
        
        if( isset( $data['entry'] ) && isset( $data['entry']->description ) ) {
            $referrer = $data['entry']->description['referrer'];
            $replace['referrer'] = $referrer;
        }
        
        if( isset( $_POST[self::$listing_id_field_name] ) ) {
            $replace['listing_id'] = $_POST[self::$listing_id_field_name];
        }

        foreach ( $replace as $key => $val ) {
            $message = str_replace('{'. $key . '}', $val, $message);
        }

        return $message;
    }


    public function contact_seller_dynamic_fields() {
        ob_start();
        $this->print_current_user_data_fields();
        return ob_get_clean();
    }

    private function print_current_user_data_fields() {
        $current_user_data = $this->get_current_user_contact_data();
        if( empty( $current_user_data ) ) {
            return;
        }
        $this->print_field_in_wrapper('Email', $current_user_data['email']);
        $this->print_field_in_wrapper('Name', $current_user_data['name']);
        
        $this->print_edit_account_details_message();
    }

    private function print_current_listing_data_fields() {
        if( ! is_singular(ListingPostType::SLUG ) ) {
            return;
        } 
        
        $listing_name = get_the_title(); 
        $this->print_field_in_wrapper('Listing', $listing_name);

    }

    public function report_abuse_dynamic_fields() {
        ob_start();
        $this->print_current_listing_data_fields();
        if( is_user_logged_in() ) {
            $this->print_current_user_data_fields();
        } else {
            $this->print_quest_fields();
        }

        return ob_get_clean();
    }

    private function print_quest_fields() {
        $this->print_field_in_wrapper('Name', '', false, self::$name_field_name, 'text', true);
        $this->print_field_in_wrapper('Email', '', false, self::$email_field_name, 'email', true);
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
        if( ! in_array($form->id, [
            $this->settings->report_abuse_form_id(), 
            $this->settings->contact_seller_form_id()
        ]) ) {
            return '';
        }
        printf('<input type="hidden" name="%s" value="%d">', self::$listing_id_field_name, get_the_id());
    }

    private function print_field_in_wrapper( $label, $val, $disabled = true, $name = '', $type = '', $required = false ) { 
        
        $disabled_attr = $type_attr = $required_attr = '';
        if( $disabled ) {
            $disabled_attr = 'disabled="disabled"';
        }
        if( ! $type ) {
            $type = 'text';
        }
        $type_attr = 'type="' . $type . '"';

        if( $required ) {
            $required_attr = 'required';
        }
        ?>
        <div class="form-field">
            <label class="frm_primary_label"><?php echo esc_html( $label ); ?></label>
            <input
                   name="<?php echo $name; ?>" 
                   value="<?php echo esc_attr( $val ); ?>" 
                   <?php echo $disabled_attr; ?>
                   <?php echo $type_attr; ?>
                   <?php echo $required_attr; ?>
            >
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
            'name' => get_user_meta( $user->ID, 'first_name', true ) . ' ' . get_user_meta( $user->ID, 'last_name', true )
        ];
    }


    private function get_guest_user_contact_data() {
        $out = [];
        if( isset( $_POST[self::$name_field_name] ) ) {
            $out['name'] = $_POST[self::$name_field_name];
        }
        
        if( isset( $_POST[self::$email_field_name] ) ) {
            $out['email'] = $_POST[self::$email_field_name];
        }
        
        return $out;
    }

    
    private function add_entry_meta( $entity_id, $field_id, $value ) {
        
        if( ! class_exists( '\FrmEntryMeta' )  ) {
            return false;
        }
        
        \FrmEntryMeta::add_entry_meta( $entity_id, $field_id, '', maybe_serialize( $value ) );
     }


    private function get_meta_field_id_for_form( $form_id ) : ?int {
        if( ! class_exists('\FrmFieldsHelper') ) {
            return null;
        }
        
        $fields = \FrmFieldsHelper::get_form_fields( $form_id );
        
        if( empty( $fields ) ) {
            return null;
        }

        foreach ( $fields as $field ) {
            if( $field->name === self::$hidden_meta_field ) {
                return $field->id;
            }
        }
        
        return null;
    }

    
    private static function get_entity_by_id( $entity ) {
        if( ! class_exists( '\FrmEntry' ) ) {
            return null;
        }
        \FrmEntry::maybe_get_entry( $entity );
        
        return $entity;
    }

    private static function get_form_id_for_entity_id( $entity_id ): ?int {
        $entry = self::get_entity_by_id( $entity_id );

        if( ! $entry || ! isset( $entry->form_id )) {
            return null;
        }
        
        return (int) $entry->form_id; 
    }

    public function after_create_entry( $entity_id, $new_values ) {

        $form_id = self::get_form_id_for_entity_id( $entity_id );
        
        
        if( is_null( $form_id ) ) {
            return;
        }
        
        $meta_field_id = self::get_meta_field_id_for_form( $form_id );
        
        if( is_null( $meta_field_id ) ) {
            return;
        }


        $data = self::prepare_entity_meta( $form_id );
            
        self::add_entry_meta( $entity_id, $meta_field_id, $data );
    }

    private function prepare_entity_meta( $form_id ) {

        $listing_data = isset( $_POST[self::$listing_id_field_name] )
            ? [ 'listing_id' => $_POST[self::$listing_id_field_name] ]
            : [];


        $user_data = $this->get_current_user_contact_data();
        if( ! is_user_logged_in()
            && (int) $form_id === $this->settings->report_abuse_form_id() ) {
            $user_data = $this->get_guest_user_contact_data();
        }
        
        return array_merge($user_data, $listing_data);
    }

}
