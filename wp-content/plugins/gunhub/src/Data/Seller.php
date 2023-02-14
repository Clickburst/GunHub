<?php
namespace GunHub\Data;


use GunHub\Infrastructure\SellerACF;
use GunHub\Infrastructure\SellerRole;

class Seller extends ACFData {

    public function get_type() {
        return $this->get_user_field(SellerACF::TYPE);
    }

    public function get_licence_id() {
        return $this->get_user_field(SellerACF::LICENCE_ID);
    }

    public function get_location() {
        return $this->get_user_field(SellerACF::LOCATION);
    }

    public function get_phone_number() {
        return get_user_meta( $this->get_ID(), 'billing_phone', true );
    }
    
    
    public function get_credits():int {
        return (int) $this->get_user_field( SellerACF::CREDITS );
    }

    public function get_credits_left_message():string {
        $credits = $this->get_credits();
        return sprintf( 'You have %s left', sprintf( _n( '%s credit', '%s credits', $credits, 'gunhub' ), $credits ) );
    }

    public function is_seller_or_admin():bool {
        if( current_user_can( 'manage_options' ) ) {
            return true;
        }

        $user = new \WP_User($this->id);
        $roles = ( array ) $user->roles;

        return in_array( SellerRole::$name, $roles );
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

    public function add_credits( $credits_to_add ) {
        $current_amount = $this->get_credits();
        $updated_amount = $current_amount + (int)$credits_to_add;
        return $this->set_user_field( SellerACF::CREDITS, $updated_amount);
    }

    public function decrease_credit( $credits_to_remove = 1 ) {
        $current_amount = $this->get_user_field( SellerACF::CREDITS );
        $updated_amount = $current_amount - (int)$credits_to_remove;
        return $this->set_user_field( SellerACF::CREDITS, $updated_amount);
    }
    
    public function update_to_seller() {
        $user = new \WP_User($this->id);
        $user->remove_role('customer');
        return $user->add_role(SellerRole::$name);
    }
}