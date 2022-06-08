<?php

namespace GunHub\Infrastructure;

use GunHub\Core\Module;

class SettingsPage {

    use Module;

    public static $contact_seller_form_id = 'contact_seller_form_id';

    
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
                        'key' => 'field_62a0841ee73e0',
                        'label' => 'Contact Seller Form ID',
                        'name' => self::$contact_seller_form_id,
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
