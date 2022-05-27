<?php declare( strict_types=1 );

namespace GunHub\Data;

class ACFData {

    public $post_id;

    public function __construct( $post_id ) {
        $this->post_id = $post_id;
    }

    protected function get_field($key) {
        if( ! function_exists( 'get_field' ) ) {
            return null;
        }

        return get_field($key, $this->post_id);
    }
    
    protected function get_term_first_item_name( $term ) {
        $terms = wp_get_post_terms($this->post_id, $term);
        
        if( is_wp_error( $terms ) ) {
            return '';
        }

        if( isset( $terms[0] ) ) {
            return $terms[0]->name;
        }
        return '';
    }
}
