<?php
namespace GunHub\Modules;


use GunHub\Core\Module;
use GunHub\Data\ListingFrontendVariables;
use GunHub\Data\Shop;
use GunHub\GunHub;
use GunHub\Infrastructure\ListingACF;

class ListingFrontendBuilder {
    
    use Module;
    
    public function init() {
        add_action('wp_head', [$this, 'maybe_print_acf_form_head'], 1);
        add_action('wp_ajax_remove_listing', [$this, 'remove_listing']);
    }

    public function maybe_print_acf_form_head() {
        if( ! function_exists('acf_form') ) {
            return;
        }
        
        if( $this->is_acf_form_head_required() ) {
            acf_form_head();
        }
    }

    public function remove_listing() {

        check_ajax_referer( 'gunhub_remove_listing', 'nonce' );
        
        $listing_id = (int) $_POST['listing_id'] ?? false;
        
        if( ! $listing_id ) {
            wp_send_json_error([
                'message' => 'missing listing ID'
            ]);
        }
        
        $listing = new \GunHub\Data\Listing( $listing_id );

        if( ! $listing->belongs_to_current_user() ) {
            wp_send_json_error([
                'message' => 'You cant delete this listing'
            ]);
        }

        if( wp_trash_post( $listing_id ) ) {
            wp_send_json_success([
                'message' => 'Listing was removed'
            ]);
        }

        wp_send_json_error([
            'message' => 'Error occurred, please contact site admin'
        ]);
    }

    private function is_acf_form_head_required() {
        $out = false;
        if ( Shop::is_wc_endpoint( ListingFrontendVariables::$new_listing_url ) ) {
            $out = true;
        } elseif ( Shop::is_wc_endpoint( ListingFrontendVariables::$my_listings_url ) && isset( $_GET[ListingFrontendVariables::$listgin_id_url] ) ) {
            $out = true;
        }
        return $out;
    }

    public static function print_add_listing_form() {
        if( ! function_exists('acf_form') ) {
            return;
        }
        acf_form_head();
        acf_form( self::get_acf_form_args() );
    }


    public static function print_edit_listing_form( $listing_Id ) {
        if( ! function_exists('acf_form') ) {
            return;
        }
        acf_form_head();
        acf_form( self::get_acf_form_args($listing_Id) );
    }

    private static function get_acf_form_args( $listing_id = null ) {
        $args = [
            'post_id'       => 'new_post',
            'post_title'    => true,
            'post_content'  => true,
            'field_groups'  => [ ListingACF::$group_id ],
            'return'        => Shop::get_my_listings_url(),
            'submit_value'  => __( 'Save Changes', 'aspirantus' ),
            'new_post'      => [
                'post_type'     => \GunHub\Infrastructure\Listing::SLUG,
                'post_status'   => 'draft'
            ],
        ];

        if( $listing_id ) {
            $args['post_id'] = $listing_id;
        }
        
        return $args;
    }

    public static function print_seller_listings() {
        $args = array(
            'author'        =>  get_current_user_id(),
            'posts_per_page' => -1,
            'post_status' => ['publish', 'draft', 'expired'],
            'post_type' => \GunHub\Infrastructure\Listing::SLUG
        );

        $posts = get_posts( $args );

        if( empty( $posts ) ) {
            echo 'nothing here yet';
            return;
        }

        foreach ( $posts as $post_obj ) {
            global $post;
            $post = get_post($post_obj);
            require GunHub::get_instance()->plugin_path . '/templates/loop/archive-item.php';
        }
        wp_reset_postdata();
    }

}
