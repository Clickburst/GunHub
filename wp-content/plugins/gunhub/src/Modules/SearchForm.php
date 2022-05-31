<?php

namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\GunHub;
use GunHub\Infrastructure\Listing;
use GunHub\Infrastructure\ListingCaliber;
use GunHub\Infrastructure\ListingCategory;
use GunHub\Infrastructure\ListingCondition;
use GunHub\Infrastructure\ListingState;


class SearchForm {

    use Module;

    public function init() {
        add_shortcode('listings_search_form', [$this, 'search_form_shortcode']);
    }

    public function search_form_shortcode() {
        $taxonomies = self::get_taxonomies_data_for_select_boxes();
        
        $keyword = $_GET['s'] ?? '';

        $listings_root = get_post_type_archive_link(Listing::SLUG);

        ob_start();        
        require_once GunHub::get_instance()->plugin_path . '/templates/search-form.php'; 
        return ob_get_clean();        
    }

    private static function get_taxonomies(): array {
        return [
            [
                'slug' => ListingCategory::SLUG,
                'label' => ListingCategory::LABEL,
            ],
            [
                'slug' => ListingCaliber::SLUG,
                'label' => ListingCaliber::LABEL,
            ],
            [
                'slug' => ListingCondition::SLUG,
                'label' => ListingCondition::LABEL,
            ],
            [
                'slug' => ListingState::SLUG,
                'label' => ListingState::LABEL,
            ],
        ];
    }

    private static function get_taxonomies_data_for_select_boxes() {
        $out = [];
        foreach ( self::get_taxonomies() as $term_data ) {
            $terms = get_terms($term_data['slug'], array( 'hide_empty' => false ) );
            $options = [];

            if( $terms ) {
                foreach ($terms as $term) {
                    $options[$term->term_id] = array(
                        'name' => $term->name,
                        'slug' => $term->slug,
                        'selected'  => isset( $_GET[$term_data['slug']] ) && $_GET[$term_data['slug']] === $term->slug
                    );
                }
            }

            if( ! empty( $options ) ) {
                $out[] = [
                    'slug'      => $term_data['slug'],
                    'title'     => ucfirst($term_data['label']),
                    'options'   => $options,
                ];
            }
        }
        return $out;
    }
}
