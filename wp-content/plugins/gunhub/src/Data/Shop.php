<?php
namespace GunHub\Data;

class Shop {

    public static function get_my_account_url($path = '') {
        $page_url = '';
        if( $page_id = get_option('woocommerce_myaccount_page_id') ) {
            $page_url = get_permalink( $page_id ); 
            if( '' !== $path ) {
                $page_url .= '/' . $path;
            }
        }
        return $page_url;
    }
}
