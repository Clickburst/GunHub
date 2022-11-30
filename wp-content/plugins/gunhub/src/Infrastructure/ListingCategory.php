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

        add_action( 'acf/init', [$this, 'register_fields'] );
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
            'rewrite' => ['slug' => 'listing-category'],
            'hierarchical' => true,
            'show_in_quick_edit' => false,
            'meta_box_cb'      => false,
        ];
    }

    public function register_fields() {
        if ( function_exists( 'acf_add_local_field_group' ) ):

            acf_add_local_field_group( array(
                'key' => 'group_6384f27f1971c',
                'title' => 'Listing Category',
                'fields' => array(
                    array(
                        'key' => 'field_6384f2956e696',
                        'label' => 'Archive H1 Override',
                        'name' => 'archive_h1_override',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_6384f46e97ac0',
                        'label' => 'Archive Text',
                        'name' => 'archive_text',
                        'type' => 'wysiwyg',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'gh-category',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ) );

        endif;
    }
}
