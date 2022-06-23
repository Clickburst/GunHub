<?php
namespace GunHub\Modules;


use GunHub\Core\Module;
use GunHub\GunHub;

class ListingFrontendBuilder {
    
    use Module;
    
    public function init() {
        add_action('acf/save_post', [$this, 'store_taxonomies']);
    }

    public function store_taxonomies(  $listing_id ) {
        
        var_dump($listing_id);
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        die;
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
            'post_id' => 'new_post',
            'post_title' => true,
            'post_content' => true,
            'field_groups' => [ 'group_628ba24e9de28' ],
            'return' => false,
            'submit_value' => __( 'Save Changes', 'aspirantus' ),
            'new_post' => [
                'post_type' => \GunHub\Infrastructure\Listing::SLUG,
                'post_status' => 'draft'
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
            'post_type' => \GunHub\Infrastructure\Listing::SLUG
        );

        $posts = get_posts( $args );

        if( empty( $posts ) ) {
            echo 'nothing here yel';
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
