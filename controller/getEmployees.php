<?php

class GetEmployees {
  
  public function action($args) {
    
    $session = Session::getInstance();
    if (!$session->getUserName()) {
      return array(
                   'success' => 'false'
                   );
    } 
    $em = EmployeeMapper::getInstance();
    return $em->getEmployees((array) $args);
    
  }
  
}