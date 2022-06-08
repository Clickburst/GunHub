<?php
namespace GunHub\Data;


use GunHub\Infrastructure\SellerACF;

class Seller extends ACFData {

    public function get_type() {
        return $this->get_user_field(SellerACF::$type);
    }

    public function get_licence_id() {
        return $this->get_user_field(SellerACF::$licence_id);
    }

    public function get_location() {
        return $this->get_user_field(SellerACF::$location);
    }

    public function get_phone_number() {
        return $this->get_user_field( SellerACF::$phone_number );
    }
    
    public function get_data() {
        $out = [];
        if( $val = $this->get_location() ) {
            $out['location'] = $val;
        }
        if( $val = $this->get_licence_id() ) {
            $out['licence_id'] = $val;
        }
        if( $val = $this->get_type() ) {
            $out['seller_type'] = $val;
        }
        if( $val = $this->get_phone_number() ) {
            $out['phone'] = $val;
        }

        return $out;
    }
    
}