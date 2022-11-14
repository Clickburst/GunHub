<?php


namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class SellerACF {

    use Module;

    public const LOCATION = 'location';
    public const LOCATION_F_ID = 'field_6295f4f5bc358';
    public const TYPE = 'type';
    public const TYPE_F_ID = 'field_6295f4e3bc357';
    
    public const LICENCE_ID = 'licence_id';
    public const LICENCE_ID_F_ID = 'field_6299cd48bbfd1';
    
    public const CREDITS = 'credits';

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
                        'key' => self::TYPE_F_ID,
                        'label' => 'Type',
                        'name' => self::TYPE,
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
                        'key' => self::LOCATION_F_ID,
                        'label' => 'Location',
                        'name' => self::LOCATION,
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
                        'key' => self::LICENCE_ID_F_ID,
                        'label' => 'Licence ID',
                        'name' => self::LICENCE_ID,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
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
                        'name' => self::CREDITS,
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