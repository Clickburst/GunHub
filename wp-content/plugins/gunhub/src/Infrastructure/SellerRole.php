<?php

namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class SellerRole {

    use Module;

    public static $name = 'seller';
    
    public function init() {
        add_action( 'init', [ $this, 'register_role' ] );
    }

    public function register_role() {
        add_role( self::$name, 'Seller', [
            'read' => true,
            'level_0' => true,
            
            'edit_gh-listing' => true,
            'upload_files' => true,

//            'delete_posts' => true, // todo - can seller delete own lisitngs?
//            'delete_published_posts' => true, // todo - can seller delete own lisitngs?
            
        ] );
    }
}
