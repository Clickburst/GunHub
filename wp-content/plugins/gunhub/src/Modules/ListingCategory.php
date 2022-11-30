<?php

namespace GunHub\Modules;

use GunHub\Core\Module;

use GunHub\Infrastructure\ListingCategory as ListingCategoryTerm;

class ListingCategory {

    use Module;
    
    public function init() {
        add_filter('get_the_archive_title', [$this, 'maybe_update_category_title_archive_page']);
        add_filter('get_the_archive_description', [$this, 'maybe_update_category_description_archive_page']);
    }

    public function maybe_update_category_title_archive_page( $term_title ) {
        if(
            is_tax(ListingCategoryTerm::SLUG)
            && function_exists('get_field')
        ) {
            global $wp_query;
            $term = $wp_query->get_queried_object();
            $header_override = get_field('archive_h1_override',$term);
            if( trim($header_override) ) {
                return trim($header_override);
            }
        }
        return $term_title;
    }
    
    public function maybe_update_category_description_archive_page($term_description) {
        if(
            is_tax(ListingCategoryTerm::SLUG)
            && function_exists('get_field')
            && empty( $_GET ) // ignore custom search results page
        ) {
            global $wp_query;
            $term = $wp_query->get_queried_object();
            $description = get_field('archive_text',$term);
            if( $description ) {
                return $description;
            }
        }
        return $term_description;
    }
}
