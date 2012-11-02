<?php
class AdministerComponent extends Component {

  public $mapMethods = array(
    'admin_add',
    'admin_edit',
  );
  
  public $components = array('Session');
  
  public function initialize(&$c, $settings = array()) {
    parent::initialize($c, $settings);
      $this->controller = $c;
    
  }  
  
  public function admin_add() {
    $single = Inflector::singularize($this->name);
    if (!empty($this->controller->request->data)) {
      $modelClass = $this->controller->modelClass;
        $this->Session->setFlash('Successfully added new '.$modelClass.'. gg.', 'alerts/success');
      if ($result = $this->controller->$modelClass->save($this->controller->request->data)) {
        $this->successRedirect();
      } else {
        $this->Session->setFlash('Problem saving '.$single.' :/', 'alerts/error');
      }
      
    }
    
  }
  
	public function admin_edit($id = null) {
  	
	  if (!empty($this->controller->request->params['named']['id'])) {
  	  $id = $this->controller->request->params['named']['id'];
	  }
    
    $modelClass = $this->controller->modelClass;
    $this->controller->$modelClass->id = $id;
     
    if (!empty($this->controller->request->data)) {
      if ($result = $this->controller->$modelClass->save($this->controller->request->data)) {
        $this->Session->setFlash('Successfully updated '.$modelClass.'. gfg.', 'alerts/success');
        $this->successRedirect();
      } else {
        $this->Session->setFlash('Problem updating your '.$modelClass.'. :(', 'alerts/error');
      }
    } else {
      $this->controller->data = $this->controller->$modelClass->read();
    }
    
    $this->controller->set(strtolower($modelClass), $this->controller->$modelClass->read());
  	
	}
	
  private function successRedirect() {
    if (!empty($this->controller->request->query['return_to'])) {
      $redirect_url = urldecode($this->controller->request->query['return_to']);

    } else {
      $redirect_url = '/'.strtolower($this->controller->name);
    }
    
    if (!empty($this->controller->request->query['id'])) {
    
      $redirect_url .= '/id:'.$this->controller->request->query['id'];
    
    }
    $this->controller->redirect($redirect_url);
  }
}

?>