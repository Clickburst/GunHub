<?php

namespace GunHub\Modules;

use GunHub\Core\Module;

use GunHub\Infrastructure\Listing as ListingPostType;
use GunHub\GunHub;
use GunHub\Infrastructure\ListingCategory;
use GunHub\Infrastructure\SellerRole;

class Listing {

    use Module;

    // todo - update before releasing
    public const EXPIRED_DAYS = 180;
    
    public function init() {
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        
        add_action('gunhub_single_listing', [$this, 'single_listing_body']);
        add_action('gunhub_single_listing', [$this, 'print_related_listings'], 15);
        add_action('gunhub_single_listing', [$this, 'blueimp_gallery'], 20);
        
        add_action('gunhub_archive_listing', [$this, 'archive_listing']);
        
        add_action('gunhub_contact_seller_form', [$this, 'print_contact_seller_form']);
        
        add_filter( 'wpcf7_before_send_mail', [$this, 'add_gun_2seller_email_to_recipient']);
        
        add_filter('pre_get_posts', [$this, 'archive_page_show_only_published_listings']);
        
        // update listings to expired each midnight 
        add_action('init', [$this, 'schedule_cron']);
        add_action('update_to_expired_old_listings', [$this, 'update_to_expired_old_listings']);
        
        
        add_shortcode('gunhub_latest_listings_for_sale', [$this, 'latest_listings_for_sale']);
    }

    public function load_assets() {
        // single page
        if( is_singular(ListingPostType::SLUG ) ) {
            wp_enqueue_script( 'blueimp-gallery', GunHub::get_instance()->plugin_url . 'js/blueimp-gallery/js/blueimp-gallery.min.js', ['jquery'], null, true );
            wp_enqueue_style( 'blueimp-gallery', GunHub::get_instance()->plugin_url . 'js/blueimp-gallery/css/blueimp-gallery.min.css' );
        }

        // todo - load assets on particular pages only?
        wp_enqueue_script( 'gunhub-front-main', GunHub::get_instance()->plugin_url . 'js/front-main.js', [ 'jquery' ], null, true );

        wp_localize_script( 'gunhub-front-main', 'gunhub', array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'ajaxnonce' => wp_create_nonce( 'gunhub_remove_listing' )
        ) );

        wp_enqueue_style( 'gunhub-front-main', GunHub::get_instance()->plugin_url . 'css/style.css', null, '1.0.1' );

        if( 
            is_archive(ListingPostType::SLUG)
            || is_front_page()
        ) {
            wp_enqueue_script( 'select2', GunHub::get_instance()->plugin_url . 'js/select2/js/select2.min.js', ['jquery'], null, true );
            wp_enqueue_style( 'select2', GunHub::get_instance()->plugin_url . 'js/select2/css/select2.min.css' );
        }
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
    
    public static function archive_listing_item() {
        require GunHub::get_instance()->plugin_path . '/templates/loop/archive-item.php';
    }

    public function print_contact_seller_form() {
        if( is_user_logged_in() ) {
            require GunHub::get_instance()->plugin_path . '/templates/parts/contact-seller-form.php';
        } else {
            require GunHub::get_instance()->plugin_path . '/templates/parts/login-to-see.php';
        }
    }

    public function print_breadcrumbs() {
        if (function_exists('rank_math_the_breadcrumbs')) {
            rank_math_the_breadcrumbs();
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
            'post_type' => ListingPostType::SLUG,
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
        self::print_listings_as_square_row($related_listings, __('You may also be interested in', 'gunhub'));
    }
    
    
    private static function print_listings_as_square_row( $related_listings, $title = '' ){
        global $post;

        echo '<div class="gunhub-square-listings">';
        if( $title !== '' ) {
            printf('<h3>%s</h3>', $title);
        }
        echo '<div class="gunhub-square-listings__items">';
        foreach ( $related_listings as $post ) {
            setup_postdata($post);
            require GunHub::get_instance()->plugin_path . '/templates/loop/related-item.php';
        }
        wp_reset_postdata();
        echo '</div>';
        echo '</div>';
    }
    
    public function archive_page_show_only_published_listings( $query ) {
        if ( $query->is_archive 
            && $query->is_search
            && $query->query_vars['post_type'] === ListingPostType::SLUG
            && ! is_admin() ) {
            
            $query->set('post_status', 'publish');
        }
        return $query;
    }

    public function schedule_cron() {
        if ( ! wp_next_scheduled( 'update_to_expired_old_listings' ) ) {
            $local_time_to_run = 'midnight';
            $timestamp = strtotime( $local_time_to_run ) - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
            wp_schedule_event(
                $timestamp,
                'daily',
                'update_to_expired_old_listings'
            );
        }
    }

    public function update_to_expired_old_listings() {
        $old_listings_ids = get_posts( array(
            'post_type' => ListingPostType::SLUG,
            'post_status' => 'publish',
            'numberposts'  => -1,
            'fields' => 'ids',
            'date_query' => [
                [
                    'before' => sprintf("%d day ago", self::EXPIRED_DAYS)
                ]
            ]
        ) );
        

        foreach ( $old_listings_ids as $old_listings_id ) {
            wp_update_post([
                'ID' => $old_listings_id,
                'post_status' => 'expired'
            ]);
        }
        // debug email
        ob_start();
        echo 'updated listings:';
        echo '<pre>';
        print_r($old_listings_ids);
        echo '</pre>';
        $out = ob_get_clean();
        wp_mail('temka789@gmail.com', 'gunhub midnight crone', $out);
    }


    public function latest_listings_for_sale() {
        $listings = get_posts([
            'post_type' => ListingPostType::SLUG,
            'numberposts' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        ob_start();
        
        self::print_listings_as_square_row($listings, __('Latest Listed For Sale', 'gunhub'));
        return ob_get_clean();
    }
}
