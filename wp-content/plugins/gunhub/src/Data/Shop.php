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
        
        return $my_account_url . ListingFrontendVariables::$my_listings_url;
    }

    public static function is_wc_endpoint($endpoint) {
        // Use the default WC function if the $endpoint is not provided
        if (empty($endpoint)) return is_wc_endpoint_url();
        // Query vars check
        global $wp;
        if (empty($wp->query_vars)) return false;
        $queryVars = $wp->query_vars;
        if (
            !empty($queryVars['pagename'])
            // Check if we are on the Woocommerce my-account page
            && $queryVars['pagename'] == 'my-account'
        ) {
            // Endpoint matched i.e. we are on the endpoint page
            if (isset($queryVars[$endpoint])) return true;
            // Dashboard my-account page special check - check whether the url ends with "my-account"
            if ($endpoint == 'dashboard') {
                $requestParts = explode('/', trim($wp->request, ' \/'));
                if (end($requestParts) == 'my-account') return true;
            }
        }
        return false;
    }
}
