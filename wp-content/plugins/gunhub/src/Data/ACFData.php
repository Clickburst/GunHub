<?php declare( strict_types=1 );

namespace GunHub\Data;

class ACFData {

    public $post_id;

    public function __construct( $post_id ) {
        $this->post_id = $post_id;
    }

    protected function get_field( $key, $for_user = false ) {
        if( ! function_exists( 'get_field' ) ) {
            return null;
        }

        $for = $this->post_id;
        if( $for_user ) {
            $for = 'user_' . $this->post_id;
        }
        
        return get_field($key, $for);
    }
    
    protected function get_user_field( $key ) {
        return $this->get_field( $key, true );
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
