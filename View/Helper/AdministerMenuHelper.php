<?php

App::uses('AppHelper','View/Helper');
class AdministerMenuHelper extends AppHelper {

	public $helpers = array('Html', 'Form');

	/**
	 * Array of Prepared/Built Menu Items designed for HTML Helper
	 * @var array
	 */
	public $menuBuild = array();

	public $menuItems = array();

	public $_default = array(
		'menu_attr' => array(),
		'item_attr' => array('escape' => false),
		'link_attr' => array(),
	);
	public $settings = array();

	/**
	* Loadable construct, pass in locale settings
	* Fail safe locale to 'en_US'
	*/
	public function __construct(View $View, $settings = array()){
		$this->_set($settings);
		$this->settings = array_merge($this->_default, $settings);
		parent::__construct($View, $settings);
	}


	public function build($items = array(), $options = array()) {
		if (!empty($options) && is_array($options)) {
			$this->settings = array_merge($this->settings, $options);
		}
		$menuBuild = array();
		foreach ($items as $i => $row) {
			$item = array(
				'anchor' => $row['anchor'],
				'path' => $row['path']
			);
			if (!empty($row['attr'])) {
				$item['attr'] = $row['attr'];
			} else {
				$item['attr'] = array();
			}
			$menuBuild[] = $item;
		}
		$this->menuBuild = $menuBuild;
	}

	public function render($items, $options = array()) {
		$this->build($items, $options);
		$items = array();
		
		// read the settings once
		$item_tag = $this->settings['item_wrap'];
		$item_attr = $this->settings['item_attr'];
		$link_attr = $this->settings['link_attr'];
		$menu_attr = $this->settings['menu_attr'];
		$menu_tag = $this->settings['menu_wrap'];

		if (!empty($this->_View->viewVars['admin_active'])) {
			$admin_active = $this->_View->viewVars['admin_active'];
		} else {
			$admin_active = '';
		}
		foreach ($this->menuBuild as $i => $row) {

			if (!empty($row['path']) && trim($row['path'])) {

				$this_link_attr = $link_attr;
				if (!empty($row['link_attr']) && is_array($row['link_attr'])) {
					$this_link_attr = array_merge($this_link_attr, $row['link_attr']);
				}
				$link = $this->Html->link($row['anchor'], $row['path'], $this_link_attr);
			} else {
				$link = $row['anchor'];
			}

			if (!empty($row['attr']) && is_array($row['attr'])) {
				$this_item_attr = array_merge($item_attr, $row['attr']);
			} else {
				$this_item_attr = $item_attr;
			}

			if ($admin_active == $row['path']) {
				$this_item_attr['class'] = 'active';
			}

			$items[] = $this->Html->tag($item_tag, $link, $this_item_attr);
		}
		$menu = implode("\n", $items);
		$menu = $this->Html->tag($menu_tag, $menu, $menu_attr);

		if (!empty($this->settings['menu_prefix'])) {
			$menu = $this->settings['menu_prefix'].$menu;
		}

		if (!empty($this->settings['menu_suffix'])) {
			$menu = $menu.$this->settings['menu_suffix'];
		}

		return $menu;
	}

	public function debug() {
		Debugger::dump($this->_View);
	}

}