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

    public static function get_my_listings_url() {
        $my_account_url = self::get_my_account_url();
        if( $my_account_url === '' ) {
            return '';
        }
        
        return $my_account_url . ListingFrontendVariables::MY_LISTINGS_URL;
    }

    public static function get_new_listing_url() {
        $my_account_url = self::get_my_account_url();
        if( $my_account_url === '' ) {
            return '';
        }
        
        return $my_account_url . ListingFrontendVariables::NEW_LISTING_URL;
    }

    public static function is_wc_endpoint($endpoint) {
        // Use the default WC function if the $endpoint is not provided
        if (empty($endpoint)) return is_wc_endpoint_url();

        $my_account_page_id = get_option('woocommerce_myaccount_page_id');
        if( empty( $my_account_page_id ) ) return is_wc_endpoint_url();
        $my_account_page_slug = get_post_field( 'post_name', $my_account_page_id );
        
        // Query vars check
        global $wp;
        if (empty($wp->query_vars)) return false;
        $queryVars = $wp->query_vars;
        if (
            !empty($queryVars['pagename'])
            // Check if we are on the Woocommerce my-account page
            && $queryVars['pagename'] == $my_account_page_slug
        ) {
            // Endpoint matched i.e. we are on the endpoint page
            if (isset($queryVars[$endpoint])) return true;
            // Dashboard my-account page special check - check whether the url ends with "my-account"
            if ($endpoint == 'dashboard') {
                $requestParts = explode('/', trim($wp->request, ' \/'));
                if (end($requestParts) == $my_account_page_slug) return true;
            }
        }
        return false;
    }
}
