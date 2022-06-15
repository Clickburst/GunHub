<?php
namespace GunHub\Data;

class Order extends \WC_Order {

    public function get_credits() {
        $credits = 0;
        foreach ( $this->get_items() as $item ) {
            $product = wc_get_product($item->get_product_id());
            $credits += (int) $product->get_attribute('credits');
        }
        return $credits;
    }
    
}