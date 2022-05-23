<?php


namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class ListingCondition {
    
    use Module;

    const SLUG = 'gh-listing-condition';

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
            'label' => __( 'Condition', 'gunhub' ),
            'rewrite' => array( 'slug' => 'condidtion' ),
            'hierarchical' => true
        ];
    }

}