<?php
class ModelExtensionModuleCustomMenuNik extends Model {
    public function install() {
        $this->log('Installing module');
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "custom_menu_items` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`menu_item_id` INT(11) NOT NULL,
			`module_id` INT(11) NOT NULL,
			`language_id` INT(11) NOT NULL,
			`parent_id` INT(11) NOT NULL,
			`name` varchar(50) NOT NULL,
			`link` varchar(255) NOT NULL,
			`class` varchar(50) NOT NULL,
			`image` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "custom_menu_items_blocks` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`menu_item_id` INT(11) NOT NULL,
			`block_id` INT(11) NOT NULL,
			`article_id` INT(11) DEFAULT NULL,
			`category_id` INT(11) DEFAULT NULL,
			`external_link_name` varchar(50) DEFAULT NULL,
			`external_link` varchar(255) DEFAULT NULL,
			`module_code` varchar(50) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "custom_menu_items`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "custom_menu_items_blocks`");

        $this->log('Module uninstalled');
    }

    public function getMenuItemBlocks($menu_item_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `menu_item_id` = '" . (int)$menu_item_id . "'");

        return $query->rows;
    }

    public function getMenuItemBlock($menu_item_id, $block_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `menu_item_id` = '" . (int)$menu_item_id . "' AND `block_id` = '" . (int)$block_id . "' ORDER BY `id` ASC");

        return $query->rows;
    }

    public function hasMenuItemBlock($menu_item_id, $block_id, $type, $id) {
        $query = array();
        if($type == 'article') {
            $query = $this->db->query("SELECT `id` FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `menu_item_id` = '" . (int)$menu_item_id . "' AND `article_id` = '" . (int)$id ."' AND `block_id` = '" . (int)$block_id . "'");
        } elseif($type == 'category') {
            $query = $this->db->query("SELECT `id` FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `menu_item_id` = '" . (int)$menu_item_id . "' AND `category_id` = '" . (int)$id ."' AND `block_id` = '" . (int)$block_id . "'");
        } elseif($type == 'module') {
            $query = $this->db->query("SELECT `id` FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `menu_item_id` = '" . (int)$menu_item_id . "' AND `module_code` = '" . $this->db->escape($id) . "' AND `block_id` = '" . (int)$block_id . "'");
        }
        return $query->row;
    }

    public function addMenuItemBlock($menu_item_id, $block_id, $block_type, $block) {
        if($block_type == 'article') {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custom_menu_items_blocks SET `menu_item_id` = '" . (int)$menu_item_id . "', `block_id` = '" . (int)$block_id ."', `article_id` = '" . (int)$block ."'");
        } elseif($block_type == 'category') {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custom_menu_items_blocks SET `menu_item_id` = '" . (int)$menu_item_id . "', `block_id` = '" . (int)$block_id ."', `category_id` = '" . (int)$block ."'");
        } elseif($block_type == 'module') {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custom_menu_items_blocks SET `menu_item_id` = '" . (int)$menu_item_id . "', `block_id` = '" . (int)$block_id ."', `module_code` = '" . $this->db->escape($block) ."'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custom_menu_items_blocks SET `menu_item_id` = '" . (int)$menu_item_id . "', `block_id` = '" . (int)$block_id ."', `external_link_name` = '" . $this->db->escape($block['name'])  ."', `external_link` = '" . $this->db->escape($block['link'])  ."'");
        }

        $this->cache->delete('custom_menu_items_blocks');
    }

    public function deleteMenuItemBlock($menu_item_block_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `id` = '" . (int)$menu_item_block_id . "'");

        $this->cache->delete('custom_menu_items_blocks');
    }

    public function deleteMenuItemBlocks($block_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `block_id` = '" . (int)$block_id . "'");

        $this->cache->delete('custom_menu_items_blocks');
    }

    public function addMenuItem($data) {
        $canAdd = false;
        foreach ($data['menu_item'] as $language_id => $value) {
            if(!empty($value['name'])) {
                $canAdd = true;
            }
        }

        $query = $this->db->query("SELECT MAX(menu_item_id) as max_id FROM " . DB_PREFIX . "custom_menu_items");

        $max_id = (int)$query->row['max_id'] + 1;

        foreach ($data['menu_item'] as $language_id => $value) {
            if($canAdd) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "custom_menu_items SET menu_item_id = '" . $max_id . "', module_id = '" . (int)$data['module_id'] . "', `language_id` = '" . (int)$language_id . "', `parent_id` = '" . (int)$value['parent_id'] . "', `name` = '" . $this->db->escape($value['name']) . "', `link` = '" . $this->db->escape($value['link']) . "', `class` = '" . $this->db->escape($value['class']) . "'");
                $menu_item_id = $this->db->getLastId();
                if (isset($value['image'])) {
                    $this->db->query("UPDATE " . DB_PREFIX . "custom_menu_items SET image = '" . $this->db->escape($value['image']) . "' WHERE `id` = '" . (int)$menu_item_id . "'");
                }
            }
        }

        $this->cache->delete('custom_menu_items');
    }

    public function editMenuItem($menu_item_id, $data) {
        foreach ($data as $language_id => $value) {
            $this->db->query("UPDATE " . DB_PREFIX . "custom_menu_items SET `parent_id` = '" . (int)$value['parent_id'] . "', `name` = '" . $this->db->escape($value['name']) . "', `link` = '" . $this->db->escape($value['link']) . "', `class` = '" . $this->db->escape($value['class']) . "' WHERE `menu_item_id` = '" . (int)$menu_item_id . "' AND `language_id` = '" . (int)$language_id . "'");

            if (isset($data['image'])) {
                $this->db->query("UPDATE " . DB_PREFIX . "custom_menu_items SET image = '" . $this->db->escape($data['image']) . "' WHERE `menu_item_id` = '" . (int)$menu_item_id . "' AND `language_id` = '" . (int)$language_id . "'");
            }
        }

        $this->cache->delete('custom_menu_items');
    }

    public function deleteMenuItem($menu_item_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "custom_menu_items WHERE `menu_item_id` = '" . (int)$menu_item_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "custom_menu_items_blocks WHERE `menu_item_id` = '" . (int)$menu_item_id . "'");

        $this->cache->delete('custom_menu_items');
        $this->cache->delete('custom_menu_items_blocks');
    }

    public function getMenuItem($menu_item_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_menu_items WHERE `menu_item_id` = '" . (int)$menu_item_id . "'"); //cmi LEFT JOIN " . DB_PREFIX . "custom_menu_items_blocks cmib ON (cmi.id = cmib.menu_item_id)

        return $query->rows;
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

    public function getMenuItemsForSelect($module_id) {
        if(isset($module_id)) {
            $sql = "SELECT * FROM " . DB_PREFIX . "custom_menu_items WHERE `module_id` = '" . (int)$module_id . "'";
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "custom_menu_items WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCategories() {
        $sql = "SELECT cp.category_id AS `id`, cd2.name AS name, c1.parent_id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sql .= " GROUP BY cp.category_id";

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
