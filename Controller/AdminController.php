<?php
/* App::uses('AdministerController', 'Administer.AdminController'); */
class AdminController extends AppController {
  
  public $components = array('Administer.Administer', 'Auth', 'Session');
  
  public function beforeFilter() {
    
  }
  
  public function index() {
    
    $this->Administer->admin_index();
    
  }
  
}

?>