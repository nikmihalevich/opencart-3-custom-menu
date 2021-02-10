<?php
class ControllerExtensionModuleCustomMenuNik extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/custom_menu_nik');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('custom_menu_nik', $this->request->post);
			} else {
			    $module = $this->model_setting_module->getModule($this->request->get['module_id']);
                $module['module_id'] = $this->request->get['module_id'];
                $module['name'] = $this->request->post['name'];
                $module['display_type'] = $this->request->post['display_type'];
                $module['status'] = $this->request->post['status'];
                $this->model_setting_module->editModule($this->request->get['module_id'], $module);
            }

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if(!isset($this->request->get['module_id'])) {
            $this->getView('add');
        } else {
		    $this->getView('edit');
        }
	}
	
	private function getView($type) {
	    if ($type == 'add') {
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->error['name'])) {
                $data['error_name'] = $this->error['name'];
            } else {
                $data['error_name'] = '';
            }

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            );

            if (!isset($this->request->get['module_id'])) {
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'], true)
                );
            } else {
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
                );
            }

            if (!isset($this->request->get['module_id'])) {
                $data['action'] = $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'], true);
            } else {
                $data['action'] = $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
            }

            $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

            if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
                $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            }

            $data['user_token'] = $this->session->data['user_token'];

            if (isset($this->request->post['name'])) {
                $data['name'] = $this->request->post['name'];
            } elseif (!empty($module_info)) {
                $data['name'] = $module_info['name'];
            } else {
                $data['name'] = '';
            }

            if (isset($this->request->post['status'])) {
                $data['status'] = $this->request->post['status'];
            } elseif (!empty($module_info)) {
                $data['status'] = $module_info['status'];
            } else {
                $data['status'] = '';
            }

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('extension/module/custom_menu_add_nik', $data));
        } else {
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->error['name'])) {
                $data['error_name'] = $this->error['name'];
            } else {
                $data['error_name'] = '';
            }

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            );

            if (!isset($this->request->get['module_id'])) {
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'], true)
                );
            } else {
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
                );
            }

            if (!isset($this->request->get['module_id'])) {
                $data['action'] = $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'], true);
            } else {
                $data['action'] = $this->url->link('extension/module/custom_menu_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
            }

            $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

            if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
                $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            }

            $data['user_token'] = $this->session->data['user_token'];
            $data['module_id']  = $this->request->get['module_id'];

            $data['extensions'] = array();
            $this->load->model('setting/extension');
            $extensions = $this->model_setting_extension->getInstalled('module');

            // Add all the modules which have multiple settings for each module
            foreach ($extensions as $code) {
                $this->load->language('extension/module/' . $code, 'extension');

                $module_data = array();

                $modules = $this->model_setting_module->getModulesByCode($code);

                foreach ($modules as $module) {
                    $module_data[] = array(
                        'name' => strip_tags($module['name']),
                        'code' => $code . '.' .  $module['module_id']
                    );
                }

                if ($this->config->has('module_' . $code . '_status') || $module_data) {
                    $data['extensions'][] = array(
                        'name'   => strip_tags($this->language->get('extension')->get('heading_title')),
                        'code'   => $code,
                        'module' => $module_data
                    );
                }
            }

            $this->load->model('extension/module/custom_menu_nik');

            $menu_items = $this->model_extension_module_custom_menu_nik->getMenuItems($this->request->get['module_id']);

            $data['select_items'] = $menu_items;

            $tree = $this->buildTree($menu_items);

//            echo "<pre>";
//            print_r($menu_items);
//            echo "</pre>";

            $data['menu_items'] = $tree;

            $this->load->model('localisation/language');

            $data['languages'] = $this->model_localisation_language->getLanguages();

            // Image
            if (isset($this->request->post['image'])) {
                $data['image'] = $this->request->post['image'];
            } elseif (!empty($module_info) && isset($module_info['image'])) {
                $data['image'] = $module_info['image'];
            } else {
                $data['image'] = '';
            }

            $this->load->model('tool/image');

            if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
                $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
            } elseif (!empty($module_info) && isset($module_info['image']) && is_file(DIR_IMAGE . $module_info['image'])) {
                $data['thumb'] = $this->model_tool_image->resize($module_info['image'], 100, 100);
            } else {
                $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

            $this->load->model('catalog/category');

            $data['categories'] = array();

            $results = $this->model_extension_module_custom_menu_nik->getCategories();

            foreach ($results as $result) {
                $link = $this->url->link('product/category', 'path=' . $result['id']);
                $link_arr = explode('/', $link);
                foreach ($link_arr as $key => $el) {
                    if($el == 'admin') {
                        unset($link_arr[$key]);
                    }
                }
                $data['categories'][] = array(
                    'id'          => $result['id'],
                    'name'        => $result['name'],
                    'parent_id'   => $result['parent_id'],
                    'link'        => implode('/', $link_arr)
                );
            }

            $data['categories'] = $this->buildTree($data['categories']);

            $this->load->model('catalog/information');

            $data['informations'] = array();

            $results = $this->model_catalog_information->getInformations();

            foreach ($results as $result) {
                $link = $this->url->link('information/information', 'information_id=' . $result['information_id']);
                $link_arr = explode('/', $link);
                foreach ($link_arr as $key => $el) {
                    if($el == 'admin') {
                        unset($link_arr[$key]);
                    }
                }
                $data['informations'][] = array(
                    'information_id' => $result['information_id'],
                    'title'          => $result['title'],
                    'link'           => implode('/', $link_arr)
                );
            }

            if (isset($this->request->post['name'])) {
                $data['name'] = $this->request->post['name'];
            } elseif (!empty($module_info)) {
                $data['name'] = $module_info['name'];
            } else {
                $data['name'] = '';
            }

            if (isset($this->request->post['menu_type'])) {
                $data['menu_type'] = $this->request->post['menu_type'];
            } elseif (!empty($module_info)) {
                $data['menu_type'] = $module_info['menu_type'];
            } else {
                $data['menu_type'] = '';
            }

            if (isset($this->request->post['display_type'])) {
                $data['display_type'] = $this->request->post['display_type'];
            } elseif (!empty($module_info)) {
                $data['display_type'] = $module_info['display_type'];
            } else {
                $data['display_type'] = '';
            }

            if (!empty($module_info)) {
                $data['type_menu'] = $module_info['type_menu'];
            } else {
                $data['type_menu'] = '';
            }

            if (isset($this->request->post['status'])) {
                $data['status'] = $this->request->post['status'];
            } elseif (!empty($module_info)) {
                $data['status'] = $module_info['status'];
            } else {
                $data['status'] = '';
            }

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('extension/module/custom_menu_edit_nik', $data));
        }
    }

    private function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function getSelectItems() {
        if(isset($this->request->get['module_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');

            $results = $this->model_extension_module_custom_menu_nik->getMenuItems($this->request->get['module_id']);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($results));
        }
    }

    public function getMenuItem() {
        if(isset($this->request->get['menu_item_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');

            $results = $this->model_extension_module_custom_menu_nik->getMenuItem($this->request->get['menu_item_id']);

            $this->load->model('tool/image');

            if (!empty($results) && isset($results['image']) && is_file(DIR_IMAGE . $results['image'])) {
                $results['thumb'] = $this->model_tool_image->resize($results['image'], 100, 100);
            } else {
                $results['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            if(isset($this->request->get['module_id'])) {
                $menu_items = $this->model_extension_module_custom_menu_nik->getMenuItems($this->request->get['module_id']);
                foreach ($menu_items as $k => $menu_item) {
                    if ($menu_item['id'] === $results['id']) {
                        unset($menu_items[$k]);
                    }
                }
                $sort_order = array();

                foreach ($menu_items as $key => $value) {
                    $sort_order[$key] = $value['name'];
                }

                array_multisort($sort_order, SORT_ASC, $menu_items);
                $results['menu_items'] = $menu_items;
            }

            $menu_items_blocks = $this->model_extension_module_custom_menu_nik->getMenuItemBlocks($this->request->get['menu_item_id']);

            $json = array();

            foreach ($menu_items_blocks as $menu_items_block) {
                if($menu_items_block['module_code']) {
                    $part = explode('.', $menu_items_block['module_code']);
                    $this->load->language('extension/module/' . $part[0], 'extension');
                    $menu_items_block['ext_name'] = strip_tags($this->language->get('extension')->get('heading_title'));
                }
                $json[$menu_items_block['block_id']][] = $menu_items_block;
            }

            $results['menu_items_blocks'] = $json;

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($results));
        }
    }

    public function getMenuItemBlocks() {
        if(isset($this->request->get['menu_item_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');

            $menu_items_blocks = $this->model_extension_module_custom_menu_nik->getMenuItemBlocks($this->request->get['menu_item_id']);

            $json = array();

            foreach ($menu_items_blocks as $menu_items_block) {
                if($menu_items_block['module_code']) {
                    $part = explode('.', $menu_items_block['module_code']);
                    $this->load->language('extension/module/' . $part[0], 'extension');
                    $menu_items_block['ext_name'] = strip_tags($this->language->get('extension')->get('heading_title'));
                }
                $json[$menu_items_block['block_id']][] = $menu_items_block;
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function getMenuItems() {
        if(isset($this->request->get['module_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');
            $json = array();

            $results = $this->model_extension_module_custom_menu_nik->getMenuItems($this->request->get['module_id']);

            $tree = $this->buildTree($results);


            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($tree));
        }
    }


    public function addMenuItems() {
        if(isset($this->request->get['module_id']) && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model('extension/module/custom_menu_nik');

            $formData = $_POST;
            $formData['module_id'] = $this->request->get['module_id'];

            $this->model_extension_module_custom_menu_nik->addMenuItem($formData);

            $this->response->setOutput('success');
        }
    }

    public function editMenuItem() {
        if(isset($this->request->get['menu_item_id']) && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model('extension/module/custom_menu_nik');

            $formData = $_POST;

            $this->model_extension_module_custom_menu_nik->editMenuItem($this->request->get['menu_item_id'], $formData['menu_item'][(int)$this->config->get('config_language_id')]);

            $this->response->setOutput('success');
        }
    }

    public function deleteMenuItem() {
        if(isset($this->request->get['menu_item_id']) && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model('extension/module/custom_menu_nik');

            $this->model_extension_module_custom_menu_nik->deleteMenuItem($this->request->get['menu_item_id']);

            $this->response->setOutput('success');
        }
    }

    public function getMenuItemBlock() {
        if(isset($this->request->get['menu_item_id']) && isset($this->request->get['block_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');

            $results = $this->model_extension_module_custom_menu_nik->getMenuItemBlock($this->request->get['menu_item_id'], $this->request->get['block_id']);

            $json = array();

            $this->load->model('catalog/information');
            $this->load->model('catalog/category');

            foreach ($results as $k => $result) {

                if($result['article_id']) {
                    $article = $this->model_catalog_information->getInformationDescriptions($result['article_id']);
                    $json[] = array(
                        'id'   => $result['id'],
                        'name' => $article[(int)$this->config->get('config_language_id')]['title']
                    );
                } else if($result['category_id']) {
                    //asd
                    $category = $this->model_catalog_category->getCategoryDescriptions($result['category_id']);
                    $json[] = array(
                        'id'   => $result['id'],
                        'name' => $category[(int)$this->config->get('config_language_id')]['name']
                    );
                } else if($result['external_link_name']) {
                    $json[] = array(
                        'id'   => $result['id'],
                        'name' => $result['external_link_name']
                    );
                } else if($result['module_code']) {
                    $this->load->model('setting/module');
                    $part = explode('.', $result['module_code']);
                    $this->load->language('extension/module/' . $part[0], 'extension');
                    if (isset($part[1])) {
                        $setting_info = $this->model_setting_module->getModule($part[1]);
                        $json[] = array(
                            'id'   => $result['id'],
                            'name' => $setting_info['name'],
                            'ext_name' => strip_tags($this->language->get('extension')->get('heading_title'))
                        );
                    } else {
                        $json[] = array(
                            'id'   => $result['id'],
                            'name' => strip_tags($this->language->get('extension')->get('heading_title')),
                        );
                    }
                }
            }


            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function addMenuItemBlock() {
        if(isset($this->request->get['menu_item_id']) && isset($this->request->get['block_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            if(isset($this->request->get['article_id'])) {
                $this->load->model('extension/module/custom_menu_nik');

                $this->model_extension_module_custom_menu_nik->addMenuItemBlock($this->request->get['menu_item_id'], $this->request->get['block_id'], 'article', $this->request->get['article_id']);

                $this->response->setOutput('success');
            } elseif(isset($this->request->get['category_id'])) {
                $this->load->model('extension/module/custom_menu_nik');

                $this->model_extension_module_custom_menu_nik->addMenuItemBlock($this->request->get['menu_item_id'], $this->request->get['block_id'], 'category', $this->request->get['category_id']);

                $this->response->setOutput('success');
            } elseif(isset($this->request->get['module_code'])) {
                $this->load->model('extension/module/custom_menu_nik');

                $this->model_extension_module_custom_menu_nik->addMenuItemBlock($this->request->get['menu_item_id'], $this->request->get['block_id'], 'module', $this->request->get['module_code']);

                $this->response->setOutput('success');
            } elseif(isset($this->request->get['link_name'])) {
                $this->load->model('extension/module/custom_menu_nik');

                $this->model_extension_module_custom_menu_nik->addMenuItemBlock($this->request->get['menu_item_id'], $this->request->get['block_id'], 'link', array('name' => $this->request->get['link_name'], 'link' => $this->request->get['link']));

                $this->response->setOutput('success');
            }
        }
    }

    public function deleteMenuItemBlock() {
        if(isset($this->request->get['menu_item_block_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');

            $this->model_extension_module_custom_menu_nik->deleteMenuItemBlock($this->request->get['menu_item_block_id']);

            $this->response->setOutput('success');
        }
    }

    public function deleteMenuItemBlocks() {
        if(isset($this->request->get['block_id']) && $this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->model('extension/module/custom_menu_nik');

            $this->model_extension_module_custom_menu_nik->deleteMenuItemBlocks($this->request->get['block_id']);

            $this->response->setOutput('success');
        }
    }

    public function install() {
        $this->load->model('extension/module/custom_menu_nik');

        $this->model_extension_module_custom_menu_nik->install();
    }

    public function uninstall() {
        $this->load->model('extension/module/custom_menu_nik');

        $this->model_extension_module_custom_menu_nik->uninstall();
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/custom_menu_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

//		if (!$this->request->post['width']) {
//			$this->error['width'] = $this->language->get('error_width');
//		}
//
//		if (!$this->request->post['height']) {
//			$this->error['height'] = $this->language->get('error_height');
//		}

		return !$this->error;
	}
}
