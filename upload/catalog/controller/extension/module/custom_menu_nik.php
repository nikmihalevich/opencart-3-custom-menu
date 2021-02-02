<?php
class ControllerExtensionModuleCustomMenuNik extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/custom_menu_nik');
		$this->load->model('extension/module/custom_menu_nik');

        $menu = $this->model_extension_module_custom_menu_nik->getMenu($setting['module_id']);

		$this->load->model('tool/image');

		foreach ($menu as $k => $item) {
            if (isset($item['image']) && is_file(DIR_IMAGE . $item['image'])) {
                $menu[$k]['thumb'] = $this->model_tool_image->resize($item['image'], 100, 100);
            } else {
                $menu[$k]['thumb'] = '';
            }
        }

		$data['setting'] = $setting;
		$data['menu'] = $menu;

//        var_dump($menu);

		return $this->load->view('extension/module/custom_menu_nik', $data);
	}
}