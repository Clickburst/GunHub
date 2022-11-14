<?php
namespace GunHub\Data;

use GunHub\Infrastructure\SettingsPage;

class Settings extends ACFData {

    public function contact_seller_form_id() {
        return $this->get_option_field(SettingsPage::CONTACT_SELLER_FORM_ID);
    }

    public function report_abuse_form_id() {
        return (int) $this->get_option_field(SettingsPage::REPORT_ABUSER_FORM_ID);
    }
    
    public function report_abuse_header() {
        return $this->get_option_field(SettingsPage::REPORT_ABUSER_HEADER);
    }
    
    public function report_abuse_body() {
        return $this->get_option_field(SettingsPage::REPORT_ABUSER_BODY);
    }
    
    public function get_place_an_ad_guest_page() {
        return $this->get_option_field(SettingsPage::PLACE_AD_QUEST_PAGE);
    }
    
    public function get_main_product_id() {
        return (int) $this->get_option_field(SettingsPage::MAIN_PRODUCT_ID);
    }
}
