<?php
namespace GunHub\Data;

use GunHub\Infrastructure\SettingsPage;

class Settings extends ACFData {

    public function contact_seller_form_id() {
        return $this->get_option_field(SettingsPage::$contact_seller_form_id);
    }

    public function report_abuse_form_id() {
        return (int) $this->get_option_field(SettingsPage::$report_abuse_form_id);
    }
    
    public function report_abuse_header() {
        return $this->get_option_field(SettingsPage::$report_abuser_header);
    }
    
    public function report_abuse_body() {
        return $this->get_option_field(SettingsPage::$report_abuser_body);
    }
}
