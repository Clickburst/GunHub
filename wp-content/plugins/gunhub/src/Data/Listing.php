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

    // todo - check image size
    public function get_featured_image_html(): string {
        if( $image_id = $this->get_featured_image_id()) {
            return wp_get_attachment_image( $image_id, 'post-medium');
        }
        return '';
    }

    public function get_featured_image_url() {
        if( $image_id = $this->get_featured_image_id()) {
            // todo - check image size
            return wp_get_attachment_image_url( $image_id, 'post-medium');
        }
        return '';
    }

    public function get_featured_image_id() {
        return $this->get_field(ListingACF::$featured_image);
    }

    public function is_editable(): bool {
        return ! in_array(get_post_status( $this->id ), ['publish', 'expired']);
    }

    public function belongs_to_current_user(): bool {
        return get_current_user_ID() === (int) get_post_field ('post_author', $this->id);
    }

    public function get_action_buttons_html(): string {
        $my_listings_url = Shop::get_my_listings_url(); 
        if( $my_listings_url === '' ) {
            return '';
        }

        $edit_url = add_query_arg(
            ListingFrontendVariables::$listgin_id_url,
            $this->id,
            $my_listings_url
        );
        ob_start();
        ?>
        <ul class="listing-actions">
            <?php if( $this->is_editable() ){ ?>
                <li><a href="<?php echo esc_url( $edit_url ); ?>" class="link link__blue">Edit</a></li>
            <?php } ?>
            <li><a href="<?php echo get_permalink( $this->id ) ?>" target="_blank" class="link link__blue">View</a></li>
            <li><a href="#" data-listing-id="<?php echo esc_attr( $this->id );?>" gh-seller-remove-listing class="link link__red">Delete</a></li>
        </ul>
        <?php
        return ob_get_clean();
    }

    public function get_pretty_status_html():string {
        return sprintf('<span class="listing-status">%s</span>', $this->get_pretty_status());
    }

    private function get_pretty_status(): string {
        $status = get_post_status($this->id);
        return self::get_pretty_statuses()[$status] ?? $status;
    }

    private static function get_pretty_statuses():array {
        return [
            'publish' => 'published',
            'draft' => 'pending review'
        ];
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
