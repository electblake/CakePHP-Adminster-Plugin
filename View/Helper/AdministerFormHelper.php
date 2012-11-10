<?php
App::import('Helper', 'Html');
class AdministerFormHelper extends FormHelper {
    // override a method
    public function input($foo, $bar) {
    
      Debugger::dump($foo);
      Debugger::dump($bar);
      // do something here, call parent if needed
      parent::input();
    }
}
?>