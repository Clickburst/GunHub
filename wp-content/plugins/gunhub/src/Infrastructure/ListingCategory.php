<?php


namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class ListingCategory {
    
    use Module;

    const SLUG = 'gh-category';
    const LABEL = 'Category';
    const LABEL_PLURAL = 'Categories';

    public function init() {
        add_action( 'init', function () {
            register_taxonomy( $this->get_slug(), $this->get_object_types(), $this->get_arguments() );
        } );
    }

    protected function get_slug() {
        return self::SLUG;
    }

    protected function get_object_types() {
        return Listing::SLUG;
    }

    protected function get_arguments() {
        return [
            'label' => self::LABEL,
            'rewrite' => ['slug' => 'category'],
            'hierarchical' => true,
            'show_in_quick_edit' => false,
            'meta_box_cb'      => false,
        ];
    }
}
