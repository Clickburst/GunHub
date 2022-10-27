<?php declare( strict_types=1 );

namespace GunHub\Data;

class ACFData {

    public $id;

    public function __construct( $id ) {
        $this->id = $id;
    }

    protected function get_field( $key, $type = '' ) {
        if( ! function_exists( 'get_field' ) ) {
            return null;
        }

        return get_field($key, $this->get_acf_for( $type ));
    }
    
    protected function get_user_field( $key ) {
        return $this->get_field( $key, 'user' );
    }
    
    protected function get_option_field( $key ) {
        return $this->get_field($key, 'option');
    }
    
    protected function get_term_first_item_name( $term ) {
        $terms = wp_get_post_terms($this->id, $term);
        
        if( is_wp_error( $terms ) ) {
            return '';
        }

        if( isset( $terms[0] ) ) {
            return $terms[0]->name;
        }
        return '';
    }
    
    protected function set_user_field( $key, $value ) {
        return $this->set_field( $key, $value, 'user' );
    }

    protected function set_field($key, $value, $type='') {
        if( ! function_exists( 'get_field' ) ) {
            return null;
        }

        return update_field( $key, $value, $this->get_acf_for( $type ) );
    }

    /**
     * Returns correct 'for' attribute, if its post, user or setting
     * 
     * @param $type
     * @return string
     */
    private function get_acf_for( $type ) {
        $for = $this->id;
        if( $type === 'user' ) {
            $for = 'user_' . $this->id;
        }elseif( $type === 'option' ) {
            $for = 'option';
        }
        return $for;
    }
}
