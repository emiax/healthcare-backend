<?php

class LogIn implements Controller {
  
  public function action($args, $lazy = false) {
    
    $session = Session::getInstance();    
    
    if (isset($args->userName) && isset($args->password)) {
      $userName = $args->userName;
      $password = $args->password;

      
      $model = EmployeeMapper::getInstance();
      $employees = $model->getEmployees(array(
                                          'userName' => $userName,
                                          'password' => $password
                                          ));
      
      $session->clearChannels();

      
      if (count($employees) === 1) {

        $employee = $employees[0];
        $session->setUserName($userName);
        

        return array(
                     'success' => true,
                     'userName' => $employee['userName'],
                     'firstName' => $employee['firstName'],
                     'lastName' => $employee['lastName'],
                     'mobileTelephone' => $employee['mobileTelephone'],
                     'homeTelephone' => $employee['homeTelephone'],
                     'address' => $employee['address'],
                     'status' => $employee['status']
                     );
      } else {
        $session->setUserName(null);
      }
      return array(
                   'success' => 'false'
                   );
      
      
    }
  }
}