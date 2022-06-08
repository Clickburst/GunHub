<?php

namespace GunHub\Modules;

use GunHub\Core\Module;

use GunHub\Infrastructure\Listing as ListingPostType;
use GunHub\GunHub;
use GunHub\Infrastructure\ListingCategory;

class Listing {

    use Module;

    public function init() {
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        
        add_action('gunhub_single_listing_body', [$this, 'single_listing_body']);
        add_action('gunhub_single_listing_body', [$this, 'print_related_listings'], 15);
        add_action('gunhub_single_listing_body', [$this, 'blueimp_gallery'], 20);
        
        add_action('gunhub_archive_listing', [$this, 'archive_listing']);
        
        add_action('gunhub_contact_seller_form', [$this, 'print_contact_seller_form']);
        
        add_filter( 'wpcf7_before_send_mail', [$this, 'add_gun_2seller_email_to_recipient']);
    }

    public function load_assets() {
        // single page
        if( is_singular(ListingPostType::SLUG ) ) {
            wp_enqueue_script( 'blueimp-gallery', GunHub::get_instance()->plugin_url . 'js/blueimp-gallery/js/blueimp-gallery.min.js', ['jquery'], null, true );
            wp_enqueue_style( 'blueimp-gallery', GunHub::get_instance()->plugin_url . 'js/blueimp-gallery/css/blueimp-gallery.min.css' );
        }

        // single and archive pages
        if( is_singular(ListingPostType::SLUG ) || is_archive(ListingPostType::SLUG) ) {
            wp_enqueue_script( 'gunhub-front-main', GunHub::get_instance()->plugin_url . 'js/front-main.js', [ 'jquery' ], null, true );
            wp_enqueue_style( 'gunhub-front-main', GunHub::get_instance()->plugin_url . 'css/style.css' );
        }

        // todo - not active yet
//        if( is_archive(ListingPostType::SLUG) ) {
//            wp_enqueue_script( 'select2', GunHub::get_instance()->plugin_url . 'js/select2/js/select2.min.js', ['jquery'], null, true );
//            wp_enqueue_style( 'select2', GunHub::get_instance()->plugin_url . 'js/select2/css/select2.min.css' );
//        }
    }

    public function single_listing_body() {
        require_once GunHub::get_instance()->plugin_path . '/templates/single-listing.php';
    }

    public function blueimp_gallery() {
        require_once GunHub::get_instance()->plugin_path . '/templates/blueimp-gallery.php';
    }

    public function archive_listing() {
        require_once GunHub::get_instance()->plugin_path . '/templates/archive-listing.php';
    }

    public function print_contact_seller_form() {
        if( is_user_logged_in() ) {
            require GunHub::get_instance()->plugin_path . '/templates/parts/contact-seller-form.php';
        } else {
            require GunHub::get_instance()->plugin_path . '/templates/parts/login-to-see.php';
        }
    }

    public function print_related_listings() {
        global $post;
        
        $terms = get_the_terms( $post, ListingCategory::SLUG );
        
        
        if( ! isset( $terms[0] ) || ! isset( $terms[0]->term_id ) ) {
            return '';
        }

        $term_id = $terms[0]->term_id;

        $args = [
            'post_type' => \GunHub\Infrastructure\Listing::SLUG,
            'numberposts' => 3,
            'post__not_in' => [$post->ID],
            'tax_query' => [
                [
                    'taxonomy' => ListingCategory::SLUG,
                    'field' => 'id',
                    'terms' => $term_id
                ]
            ]
        ];

        $related_listings = get_posts( $args ); 
        if( empty( $related_listings ) ) {
            return '';
        }
        echo '<div class="gunhub-related">';
        printf('<h3>%s</h3>', __('You may also be interested in', 'gunhub'));
        echo '<div class="gunhub-related__items">';
        foreach ( $related_listings as $post ) {
            setup_postdata($post);
            require GunHub::get_instance()->plugin_path . '/templates/loop/related-item.php';
        }
        echo '</div>';
        echo '</div>';
        wp_reset_postdata();

    }
    
//    public function add_gun_seller_email_to_recipient( $contact_form ) {
//        return $contact_form;
//    }
}
