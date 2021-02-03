<?php
class ModelExtensionModuleCustomMenuNik extends Model {
    public function getMenu($module_id, $language_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_menu_items` WHERE module_id = '" . (int)$module_id . "' AND language_id = '" . (int)$language_id . "'");

        return $query->rows;
    }

    public function getBlocks($menu_item_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_menu_items_blocks` WHERE menu_item_id = '" . (int)$menu_item_id . "'");

        return $query->rows;
    }
}