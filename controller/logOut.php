<?php

class LogOut implements Controller {
  
  public function action($args, $lazy = false) {

    $session->clearChannels();
    $session->setUserName('');
    
    return array('success' => true);
  }
  
}