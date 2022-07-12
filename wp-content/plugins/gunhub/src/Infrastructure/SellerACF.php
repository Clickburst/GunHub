<?php


namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class SellerACF {

    use Module;

    public static $location = 'location';
    public static $type = 'type';
    public static $phone_number = 'phone_number';
    public static $licence_id = 'licence_id';
    public static $credits = 'credits';
//    public static $is_seller = 'is_seller';

    public function init() {
        add_action( 'acf/init', [$this, 'register_fields'] );
    }

    public function register_fields() {
        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_6295f4d324db0',
                'title' => 'Seller',
                'fields' => array(
//                    array(
//                        'key' => 'field_62ac6cee40225',
//                        'label' => 'Is Seller',
//                        'name' => self::$is_seller,
//                        'type' => 'true_false',
//                        'instructions' => '',
//                        'required' => 0,
//                        'conditional_logic' => 0,
//                        'wrapper' => array(
//                            'width' => '',
//                            'class' => '',
//                            'id' => '',
//                        ),
//                        'message' => '',
//                        'default_value' => 0,
//                        'ui' => 0,
//                        'ui_on_text' => '',
//                        'ui_off_text' => '',
//                    ),
                    array(
                        'key' => 'field_6295f4e3bc357',
                        'label' => 'Type',
                        'name' => self::$type,
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'private' => 'Private User',
                            'company' => 'Company',
                        ),
                        'default_value' => false,
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_6295f4f5bc358',
                        'label' => 'Location',
                        'name' => self::$location,
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
                        'key' => 'field_62960e7b90786',
                        'label' => 'Phone Number',
                        'name' => self::$phone_number,
                        'type' => 'number',
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
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_6299cd48bbfd1',
                        'label' => 'Licence ID',
                        'name' => self::$licence_id,
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
                        'key' => 'field_62a89addd6ca4',
                        'label' => 'Credits',
                        'name' => self::$credits,
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '0',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'user_role',
                            'operator' => '==',
                            'value' => 'all',
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
            ));

        endif;
    }

}