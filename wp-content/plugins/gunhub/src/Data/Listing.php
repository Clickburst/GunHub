<?php
namespace GunHub\Data;


use GunHub\Infrastructure\ListingACF;
use GunHub\Infrastructure\ListingCaliber;
use GunHub\Infrastructure\ListingCategory;
use GunHub\Infrastructure\ListingCondition;
use GunHub\Infrastructure\ListingState;

class Listing extends ACFData {
    
    private $currency = '$';
    

    public function get_price() {
        $price = $this->get_field( ListingACF::$price );
        
        if( '' !== $price) {
            return $this->currency . $price;
        }
        
        return '';
    }
    
    public function get_serial_no() {
        return $this->get_field( ListingACF::$serial_no );
    }
    public function get_gallery() {
        return $this->get_field( ListingACF::$gallery );
    }
    
    public function get_phone_number() {
        return $this->get_field( ListingACF::$phone_number );
    }

    public function get_make() {
        return $this->get_field( ListingACF::$make );
    }

    public function get_model() {
        return $this->get_field( ListingACF::$model );
    }

    public function get_license_no() {
        return $this->get_field( ListingACF::$license_no );
    }

    public function get_action() {
        return $this->get_field( ListingACF::$action );
    }
    public function get_sights() {
        return $this->get_field( ListingACF::$sights );
    }

    public function get_condition(): string {
        return $this->get_term_first_item_name(ListingCondition::SLUG);
    }
    
    public function get_calibre(): string {
        return $this->get_term_first_item_name(ListingCaliber::SLUG);
    }
    private function get_category(): string {
        return $this->get_term_first_item_name(ListingCategory::SLUG);
    }
    public function get_state(): string {
        return $this->get_term_first_item_name(ListingState::SLUG);
    }


    public function get_attributes_list(): array {
        return [
            'condition' => $this->get_condition(),
            'ammo_type' => $this->get_category(),
            'make' => $this->get_make(),
            'model' => $this->get_model(),
            'action_type' => $this->get_action(),
            'calibre' => $this->get_calibre(),
            'serial_number' => $this->get_serial_no(),
        ];
    }
}
