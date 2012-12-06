<?php

class LogOut implements Controller {
  
  public function action($args, $lazy = false) {
    
    $session = Session::getInstance();  

    $session->clearChannels();
    $session->setUserName('');
    
    return array('success' => true);
  }
  
}