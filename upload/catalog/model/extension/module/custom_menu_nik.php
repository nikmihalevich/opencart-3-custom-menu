<?php
class ModelExtensionModuleCustomMenuNik extends Model {
    public function getMenu($module_id) {

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_menu_items` WHERE module_id = '" . (int)$module_id . "'");

        return $query->rows;

    }
}