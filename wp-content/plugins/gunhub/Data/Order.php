<?php
namespace GunHub\Data;


class Order extends \WC_Order {

    public function __construct() {
//        add_action('woocommerce_new_order', [$this, 'update_order_acf_fields']);
//        add_action('woocommerce_checkout_update_order_meta', [$this, 'al_update_order_total_wight_height_acf']);
    }

    public function update_order_acf_fields( $order_id ) {
        
    }

    public function al_update_order_total_wight_height_acf($order_id){
        $total_height = al_get_order_products_total_height($order_id);
        $total_weight = al_get_order_products_total_weight($order_id);

        update_field('total_product_weight', $total_weight, $order_id);
        update_field('total_product_height', $total_height, $order_id);
    }
    

}