<?php
namespace GunHub\Modules;

use GunHub\Core\Module;

class Ads {

    use Module;
    
    public function init() {
        add_action('gunhub_listings_loop_after_nth_item', [$this, 'place_ad_after_nth_item']);
    }
    
    public function place_ad_after_nth_item( $item_number ){
        if( $item_number === 5 ) {
            self::print_ad_inside_listings_loop();
        }
    }

    private static function print_ad_inside_listings_loop() {
        ?>
        <div class="listing-loop" style="padding: 15px;">
            <h3>some ad goes here</h3>
        </div>
        <?php
    }
}
