<?php

class LoginController {
  
  public function output($args, $forceResponse) {
    
    $session = Session::getInstance();    
    $stateChanged = $session->updateState('usersOnline', 5);
    
    if ($forceResponse || $stateChanged) {
      return array('5');
    } else {
      return null;
    }
    
  }

}