<?php
class AdministerComponent extends Component {
  
  public $components = array('Session');
  public $helpers = array('Administer.AdministerForm');
  
  public function initialize(&$c, $settings = array()) {
    parent::initialize($c, $settings);
    $this->controller = $c;
    $this->_single = Inflector::singularize($this->controller->name);
    $this->controller->set('singleModel', $this->_single);
    $this->controller->set('pluralModel', $this->controller->name);
    $this->controller->set('is_'.strtolower($this->controller->name), true);
  }
  
  public function beforeRender($c) {
    $c->set('admin_active', $c->request->here);
  }

  public function admin_index() {
    
    $modelClass = $this->controller->modelClass;
    $objects = $this->controller->$modelClass->find('all');
    $setVar = strtolower(Inflector::pluralize($this->controller->modelClass));
    $this->controller->set($setVar, $objects);
    $this->setReturnTo();
  }
  
  public function admin_remove($id = null) {
    
	  if (!empty($this->controller->request->params['named']['id'])) {
  	  $id = $this->controller->request->params['named']['id'];
	  }
	  
    $modelClass = $this->controller->modelClass;
    if ($result = $this->controller->$modelClass->delete($id)) {
      $this->Session->setFlash('Successfully removed '.$modelClass.'. gfg.', 'alerts/success');
      $this->finishedRedirect();
    } else {      
      $this->Session->setFlash('Problem removing '.$single.' :/', 'alerts/error');
      $this->finishedRedirect();
    }
    $this->setReturnTo();
  }
  
  public function admin_add() {
    if (!empty($this->controller->request->data)) {
      $single = $this->_single;
      $modelClass = $this->controller->modelClass;
      $this->Session->setFlash('Successfully added new '.$modelClass.'. gg.', 'alerts/success');
      if ($result = $this->controller->$modelClass->save($this->controller->request->data)) {
        $this->finishedRedirect();
      } else {
        $this->Session->setFlash('Problem saving '.$this->single.' :/', 'alerts/error');
      }
    }
    
    $this->setReturnTo();
  }
  
  
	public function admin_edit($id = null) {
  	
	  if (!empty($this->controller->request->params['named']['id'])) {
  	  $id = $this->controller->request->params['named']['id'];
	  }
    
    $modelClass = $this->controller->modelClass;
    $this->controller->$modelClass->id = $id;
     
    if (!empty($this->controller->request->data)) {
      if ($result = $this->controller->$modelClass->save($this->controller->request->data)) {
        $this->Session->setFlash('Successfully updated '.$modelClass.' gfg.', 'alerts/success');
        $this->finishedRedirect();
      } else {
        $this->Session->setFlash('Problem updating your '.$modelClass.' :(', 'alerts/error');
        
        foreach ($this->controller->$modelClass->invalidFields() as $field) {
          foreach ($field as $error) {
            $this->Session->setFlash($error, 'alerts/error');
          }
        }
        
      }
    } else {
      $this->controller->data = $this->controller->$modelClass->read();
    }
    
    $this->controller->set(strtolower($modelClass), $this->controller->$modelClass->read());
    $this->setReturnTo();
  	
	}

  public function getReturnTo() {
    $return_to = '';
    if (!empty($this->controller->request->params['named']['return_to'])) {
      $return_to = urldecode($this->controller->request->params['named']['return_to']);
    }
    if (!empty($this->controller->request->query['return_to'])) {
      $return_to = urldecode($this->controller->request->query['return_to']);
    }
    return $return_to;
  }

  public function setReturnTo($default = null) {
    $default = empty($default) ? $_SERVER['REQUEST_URI'] : $default;

    $return_to = $this->getReturnTo();
    if (!$return_to) {
      $return_to = $default;
    }

    $this->controller->set('return_to', $return_to);

  }

  public function setActive($path) {
    $this->controller->set('admin_active', $path);
  }
  private function finishedRedirect() {
    $this->controller->redirect($this->_getRedirectUrl());
  }
  
  private function _getRedirectUrl() {
    if (!empty($this->controller->request->query['return_to'])) {
      $redirect_url = urldecode($this->controller->request->query['return_to']);

    } elseif (!empty($this->controller->request->data['return_to'])) {
      
      $redirect_url = urldecode($this->controller->request->data['return_to']);
      
    } else {
      $redirect_url = '/'.strtolower($this->controller->name);
    }
    
    if (!empty($this->controller->request->query['id'])) {
      $redirect_url .= '/id:'.$this->controller->request->query['id'];
    }
    return $redirect_url;
  }
  
}

?>