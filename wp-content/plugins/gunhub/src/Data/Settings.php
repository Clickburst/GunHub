<?php
namespace GunHub\Data;

use GunHub\Infrastructure\SettingsPage;

class Settings extends ACFData {

    public function contact_seller_form_id() {
        return $this->get_option_field(SettingsPage::$contact_seller_form_id);
    }
}
