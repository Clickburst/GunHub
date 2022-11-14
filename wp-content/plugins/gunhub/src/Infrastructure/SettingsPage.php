<?php

namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class SettingsPage {

    use Module;

    public const CONTACT_SELLER_FORM_ID = 'contact_seller_form_id';
    public const REPORT_ABUSER_FORM_ID = 'report_abuse_form_id';
    public const REPORT_ABUSER_HEADER = 'report_abuse_header';
    public const REPORT_ABUSER_BODY = 'report_abuse_body';

    public const PLACE_AD_QUEST_PAGE = 'place_an_ad_guest_page';
    
    public const MAIN_PRODUCT_ID = 'main_product_id';

    
    public function init() {
        add_action( 'acf/init', [ $this, 'register_options_page' ] );
        add_action( 'acf/init', [ $this, 'register_options_page_field' ] );
    }

    public function register_options_page() {
        if( function_exists('acf_add_options_page') ) {
            acf_add_options_page(array(
                'page_title' 	=> 'GunHub Settings',
                'menu_title'	=> 'GunHub Settings',
                'menu_slug' 	=> 'gunhub-setting',
                'capability'	=> 'manage_options',
                'redirect'		=> false
            ));
        }
    }

    public function register_options_page_field() {
        if( function_exists('acf_add_local_field_group') ) {
            acf_add_local_field_group(array(
                'key' => 'group_62a0840ceafd3',
                'title' => 'Gunhub Settings',
                'fields' => array(
                    array(
                        'key' => 'field_62cea2be01154',
                        'label' => 'Forms',
                        'name' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_62a0841ee73e0',
                        'label' => 'Contact Seller Form ID',
                        'name' => self::CONTACT_SELLER_FORM_ID,
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
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
                        'key' => 'field_62d036f9a8dbb',
                        'label' => 'Report Abuse Form ID',
                        'name' => self::REPORT_ABUSER_FORM_ID,
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
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
                        'key' => 'field_62cea2cd01155',
                        'label' => 'Report Abuse',
                        'name' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_62cea2d701156',
                        'label' => 'Report Abuse Header',
                        'name' => self::REPORT_ABUSER_HEADER,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 'Stay Safe from Scammers',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_62cea2e101157',
                        'label' => 'Report Abuse Body',
                        'name' => self::REPORT_ABUSER_BODY,
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 'We understand that online transactions can be simple and convenient, but please remember that meeting face to face is the best way to minimise the risk of fraudulent activity. Additionally, we\'ve been made aware of people reusing images from the internet in scam listings -- use a Reverse Image Search to check if this is happening. This is not targeted at any specific listing but rather a general reminder for our users. If something is fishy, use the report button',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => '',
                    ),
                    array(
                        'key' => 'field_62dfc31673619',
                        'label' => 'Pages',
                        'name' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_62dfc3207361a',
                        'label' => 'Place An Ad Guest Page',
                        'name' => self::PLACE_AD_QUEST_PAGE,
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'page',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_62dfc31673620',
                        'label' => 'Products',
                        'name' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_62dfc3207362a',
                        'label' => 'Main Product ID',
                        'name' => self::MAIN_PRODUCT_ID,
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'product',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'gunhub-setting',
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

        }
    }
}
