<?php

class GetSchedule {
  
  public function action($args) {
    $session = Session::getInstance();
    
    $userName = $session->getUserName();
    
    return array('userName' => $userName);
    
  }

}