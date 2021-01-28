<?php
class ModelExtensionModuleCustomMenuNik extends Model
{
    public function install() {
        $this->log('Installing module');
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "custom_menu_items` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`module_id` INT(11) NOT NULL,
			`language_id` INT(11) NOT NULL,
			`parent_id` INT(11) NOT NULL,
			`name` varchar(50) NOT NULL,
			`link` varchar(255) NOT NULL,
			`class` varchar(50) NOT NULL,
			`image` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "custom_menu_items`");

        $this->log('Module uninstalled');
    }

    public function addMenuItem($data) {
        foreach ($data['menu_item'] as $language_id => $value) {
            if(!empty($value['name'])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "custom_menu_items SET module_id = '" . (int)$data['module_id'] . "', `language_id` = '" . (int)$language_id . "', `parent_id` = '" . (int)$value['parent_id'] . "', `name` = '" . $this->db->escape($value['name']) . "', `link` = '" . $this->db->escape($value['link']) . "', `class` = '" . $this->db->escape($value['class']) . "'");
            }
            $menu_item_id = $this->db->getLastId();
            if (isset($value['image'])) {
                $this->db->query("UPDATE " . DB_PREFIX . "custom_menu_items SET image = '" . $this->db->escape($value['image']) . "' WHERE `id` = '" . (int)$menu_item_id . "'");
            }
        }

        $this->cache->delete('custom_menu_items');
    }

    public function editMenuItem($menu_item_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "custom_menu_items SET `parent_id` = '" . (int)$data['parent_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `link` = '" . $this->db->escape($data['link']) . "', `class` = '" . $this->db->escape($data['class']) . "' WHERE `id` = '" . (int)$menu_item_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "custom_menu_items SET image = '" . $this->db->escape($data['image']) . "' WHERE `id` = '" . (int)$menu_item_id . "'");
        }

        $this->cache->delete('custom_menu_items');
    }

    public function deleteMenuItem($menu_item_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "custom_menu_items WHERE `id` = '" . (int)$menu_item_id . "'");

        $this->cache->delete('custom_menu_items');
    }

    public function getMenuItem($menu_item_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "custom_menu_items WHERE `id` = '" . (int)$menu_item_id . "'");

        return $query->row;
    }

    public function getMenuItems($module_id) {
        if(isset($module_id)) {
            $sql = "SELECT * FROM " . DB_PREFIX . "custom_menu_items WHERE `module_id` = '" . (int)$module_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'";
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "custom_menu_items WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function log($data) {
        // if ($this->config->has('payment_stripe_logging') && $this->config->get('payment_stripe_logging')) {
        $log = new Log('custom_menu_items.log');

        $log->write($data);
        // }
    }
}
