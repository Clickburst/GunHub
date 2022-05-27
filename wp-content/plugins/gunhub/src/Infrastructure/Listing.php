<?php


namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class Listing {
    
    use Module;

    const SLUG = 'gh-listing';

    public function init() {
        add_action( 'init', function () {
            register_post_type( $this->get_slug(), $this->get_arguments() );
        } );
    }

    protected function get_slug() {
        return self::SLUG;
    }

    protected function get_arguments() {
        $labels = [
            'name'                => _x( 'Listings', 'Post Type General Name', 'gunhub' ),
            'singular_name'       => _x( 'Listing', 'Post Type Singular Name', 'gunhub' ),
            'menu_name'           => __( 'Listings', 'gunhub' ),
            'parent_item_colon'   => __( 'Parent Listing', 'gunhub' ),
            'all_items'           => __( 'All Listings', 'gunhub' ),
            'view_item'           => __( 'View Listing', 'gunhub' ),
            'add_new_item'        => __( 'Add New Listing', 'gunhub' ),
            'add_new'             => __( 'Add New', 'gunhub' ),
            'edit_item'           => __( 'Edit Listing', 'gunhub' ),
            'update_item'         => __( 'Update Listing', 'gunhub' ),
            'search_items'        => __( 'Search Listing', 'gunhub' ),
            'not_found'           => __( 'Not Found', 'gunhub' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'gunhub' ),
        ];


        $args = [
            'label'               => __( 'Listings', 'gunhub' ),
            'description'         => '',
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => [ 'title', 'editor', 'author', 'thumbnail','custom-fields'],
            // You can associate this CPT with a taxonomy or custom taxonomy. 
            'taxonomies'          => [ListingCategory::SLUG],
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => true,
            'show_in_admin_bar'     => true,
            'menu_position'         => 5,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'menu_icon'             => 'dashicons-shield-alt',
            'rewrite'               => ['slug' => 'listings','with_front' => false]
        ];
        

        return $args;
    }

}