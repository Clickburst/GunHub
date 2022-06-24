<?php

namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class SellerRole {

    use Module;

    public static $name = 'seller';
    
    public function init() {
        add_action( 'init', [ $this, 'register_role' ] );
        add_filter('ajax_query_attachments_args', [$this, 'show_only_current_user_attachments']);
    }

    // todo - check edit published access
    public function register_role() {
        add_role( self::$name, 'Seller', [
            'read' => true,
            'level_0' => true,
            'edit_gh-listing' => true,
            'edit_published_gh-listing' => false,
            'delete_gh-listing' => true,
            'delete_published_gh-listing' => true,
            'upload_files' => true,
//            'delete_posts' => true, // todo - can seller delete own lisitngs?
        ] );
    }

    public function show_only_current_user_attachments($query) {
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        if( in_array( SellerRole::$name, $roles ) ) {
            $query['author'] = $user->ID;
//            $query['post_parent'] = 0;
        }

        return $query;
    }

}
