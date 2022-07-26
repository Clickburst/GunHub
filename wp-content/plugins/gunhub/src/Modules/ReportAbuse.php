<?php

namespace GunHub\Modules;

use GunHub\Core\Module;
use GunHub\Data\Settings;
use GunHub\GunHub;
use GunHub\Infrastructure\Listing as ListingPostType;

class ReportAbuse {

    use Module {
        Module::__construct as private __ModuleConstruct;
    }

    public function __construct()  {
        $this->__ModuleConstruct();

        $this->settings = new Settings( false );
    }

    public function init() {
        add_action('gunhub_before_listing_description', [$this, 'print_message']);
        add_action('gunhub_before_listing_description', [$this, 'print_modal']);
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
    }

    public function load_assets() {
        if( is_singular( ListingPostType::SLUG ) ) {
            wp_enqueue_script( 'jquery-modal', GunHub::get_instance()->plugin_url . 'js/jquery-modal/jquery.modal.min.js', [ 'jquery' ], null, true );
            wp_enqueue_style( 'jquery-modal', GunHub::get_instance()->plugin_url . 'js/jquery-modal/jquery.modal.min.css' );
        }
    }

    public function print_message() {
        $header = $this->settings->report_abuse_header();
        $body = $this->settings->report_abuse_body();
        ?>
        <div class="gunhub-report-abuse">
            <a class="gunhub-report-abuse__close" gunhub-hide-parent>
                <span aria-hidden="true">×</span>
            </a>
            <h4 class="gunhub-report-abuse__header"><?php echo esc_html( $header ); ?></h4>
            <p class="gunhub-report-abuse__body"><?php echo esc_html( $body ); ?></p>
        </div>
        
        <p><a href="#ex1" class="btn btn__red" rel="modal:open">Report</a></p>

        <?php
    }

    public function print_modal() { ?>
        <div id="ex1" class="modal">
            <?php echo do_shortcode('[formidable id=reportabuse user_id=current]') ?> 
            <a href="#" rel="modal:close">Close</a>
        </div>
        <?php
    }
}
