<?php


namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class ListingACF {

    use Module;

    public static $group_id = 'group_628ba24e9de28';
    public static $price = 'price';
    public static $serial_no = 'serial_no';
    public static $gallery = 'gallery';
    public static $featured_image = 'featured_image';
    public static $make = 'make';
    public static $model = 'model';
    public static $license_no = 'license_no';
    public static $action = 'action';
    public static $sights = 'sights';

    public function init() {
        add_action( 'acf/init', [$this, 'register_fields'] );
    }

    protected function register_fields() {
        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => self::$group_id,
                'title' => 'Listing',
                'fields' => array(
                    array(
                        'key' => 'field_62ac8b9de9a25',
                        'label' => 'Featured Image',
                        'name' => self::$featured_image,
                        'type' => 'image',
                        'instructions' => 'used on archive page',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                    array(
                        'key' => 'field_628ba2cdec775',
                        'label' => 'Gallery',
                        'name' => self::$gallery,
                        'type' => 'gallery',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'insert' => 'append',
                        'library' => 'all',
                        'min' => '',
                        'max' => '',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                    array(
                        'key' => 'field_628ba27bec76e',
                        'label' => 'Make',
                        'name' => self::$make,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
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
                        'key' => 'field_628ba288ec76f',
                        'label' => 'Model',
                        'name' => self::$model,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
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
                        'key' => 'field_628ba293ec770',
                        'label' => 'Action',
                        'name' => self::$action,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
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
                        'key' => 'field_628ba2a0ec771',
                        'label' => 'Sights',
                        'name' => self::$sights,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
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
                        'key' => 'field_628ba2a6ec772',
                        'label' => 'Price',
                        'name' => self::$price,
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_628ba2b9ec773',
                        'label' => 'Serial No',
                        'name' => 'serial_no',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
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
                        'key' => 'field_628ba2bfec774',
                        'label' => 'License No',
                        'name' => 'license_no',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
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
                        'key' => 'field_62b477fe001c5',
                        'label' => 'Category',
                        'name' => 'category',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'taxonomy' => 'category',
                        'field_type' => 'select',
                        'allow_null' => 0,
                        'add_term' => 1,
                        'save_terms' => 1,
                        'load_terms' => 1,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ),
                    array(
                        'key' => 'field_62b47bf595b65',
                        'label' => 'Condition',
                        'name' => 'condition',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'taxonomy' => 'condition',
                        'field_type' => 'select',
                        'allow_null' => 0,
                        'add_term' => 1,
                        'save_terms' => 1,
                        'load_terms' => 1,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ),
                    array(
                        'key' => 'field_62b47c406ece4',
                        'label' => 'Caliber',
                        'name' => 'caliber',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'taxonomy' => 'caliber',
                        'field_type' => 'select',
                        'allow_null' => 0,
                        'add_term' => 1,
                        'save_terms' => 1,
                        'load_terms' => 1,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ),
                    array(
                        'key' => 'field_62b47c738ee29',
                        'label' => 'State',
                        'name' => 'state',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'taxonomy' => 'state',
                        'field_type' => 'select',
                        'allow_null' => 0,
                        'add_term' => 1,
                        'save_terms' => 1,
                        'load_terms' => 1,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'gh-listing',
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
            ));;

        endif;
    }

}