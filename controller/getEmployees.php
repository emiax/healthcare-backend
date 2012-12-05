<?php

class GetEmployees implements Controller {
  
  public function action($args, $lazy) {
    
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