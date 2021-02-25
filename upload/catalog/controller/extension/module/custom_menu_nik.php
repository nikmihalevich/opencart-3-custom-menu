<?php
class ControllerExtensionModuleCustomMenuNik extends Controller {
	public function index($setting) {
        static $module = 0;
		$this->load->language('extension/module/custom_menu_nik');
		$this->load->model('extension/module/custom_menu_nik');

		$data = array();

		if(isset($setting['module_id'])) {
            $menu = $this->model_extension_module_custom_menu_nik->getMenu($setting['module_id'], $this->config->get('config_language_id'));

            $this->load->model('tool/image');

            foreach ($menu as $k => $item) {
                if (isset($item['image']) && is_file(DIR_IMAGE . $item['image'])) {
                    $menu[$k]['thumb'] = $this->model_tool_image->resize($item['image'], 100, 100);
                } else {
                    $menu[$k]['thumb'] = '';
                }
                if($setting['type_menu'] != '1') {
                    $blocks = $this->model_extension_module_custom_menu_nik->getBlocks($item['menu_item_id']);
                    $item_blocks = array();
                    if ($blocks) {
                        foreach ($blocks as $kk => $block) {
                            if ($block['article_id']) {
                                $this->load->model('catalog/information');
                                $information_info = $this->model_catalog_information->getInformation($block['article_id']);
                                $item_blocks[$block['block_id']][] = array(
                                    'link_name' => $information_info['title'],
                                    'link' => $this->url->link('information/information', 'information_id=' . $block['article_id'])
                                );
                            } else if ($block['category_id']) {
                                $this->load->model('catalog/category');
                                $category_info = $this->model_catalog_category->getCategory($block['category_id']);
                                $item_blocks[$block['block_id']][] = array(
                                    'link_name' => $category_info['name'],
                                    'link' => $this->url->link('product/category', 'path=' . $block['category_id'])
                                );
                            } else if ($block['external_link_name']) {
                                $item_blocks[$block['block_id']][] = array(
                                    'link_name' => $block['external_link_name'],
                                    'link' => $block['external_link']
                                );
                            } else if ($block['module_code']) {
                                $part = explode('.', $block['module_code']);

                                if (isset($part[0]) && $this->config->get('module_' . $part[0] . '_status')) {
                                    $module_data = $this->load->controller('extension/module/' . $part[0]);

                                    if ($module_data) {
                                        $item_blocks[$block['block_id']][] = array(
                                            'module' => $module_data
                                        );
                                    }
                                }

                                if (isset($part[1])) {
                                    $setting_info = $this->model_setting_module->getModule($part[1]);

                                    if ($setting_info && $setting_info['status']) {
                                        $output = $this->load->controller('extension/module/' . $part[0], $setting_info);

                                        if ($output) {
                                            $item_blocks[$block['block_id']][] = array(
                                                'module' => $output
                                            );
                                        }
                                    }
                                }
                            }
                        }

                        $menu[$k]['blocks'] = $item_blocks;
                    }
                }
            }

            $data['setting'] = $setting;
            $data['menu'] = $this->buildTree($menu);
        }

//		echo "<pre>";
//		print_r($data);
//		echo "</pre>";

        $data['module'] = $module++;

		return $this->load->view('extension/module/custom_menu_nik', $data);
	}

    private function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['menu_item_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}