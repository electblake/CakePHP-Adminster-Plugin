<?php

App::uses('AppHelper','View/Helper');
class AdministerHelper extends AppHelper {

	public $helpers = array('Form', 'Html');
	/**
	* Loadable construct, pass in locale settings
	* Fail safe locale to 'en_US'
	*/
	public function __construct(View $View, $settings = array()){
		$this->_set($settings);
		parent::__construct($View, $settings);
	}

	public function returnToInput() {
		if ($this->getReturnTo()) {
			return $this->Form->input('return_to', array(
	      'type' => 'hidden',
	      'name' => 'return_to',
	      'value' => $this->getReturnTo(),
	    ));
		}
		return '';
	}

	public function backLink() {
		if ($this->getReturnTo()) {
			return '<a href="'.$this->getReturnTo().'" class="administer-goback"><i class="icon-arrow-left"></i> Go Back</a>';
		}
		return '';
	}

	public function getReturnTo() {
		if (!empty($this->_View->viewVars['return_to'])) {
			return $this->_View->viewVars['return_to'];	
		}
		return false;
	}

	public function isActive($path) {
		if (!empty($this->_View->viewVars['admin_active'])) {
			return stripos($this->_View->viewVars['admin_active'], $path) !== false;
		}
	}

	public function activeClass($path) {
		if ($this->isActive($path)) {
			return 'active';
		} else {
			return '';
		}
	}
}

?>