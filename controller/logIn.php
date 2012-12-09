<?php

class LogIn implements Controller {
  
  public function action($args, $lazy = false) {
    
    $session = Session::getInstance();    

    if (isset($args->username) && isset($args->password)) {
      $username = $args->username;
      $password = $args->password;

      
      $model = EmployeeMapper::getInstance();
      $employees = $model->getEmployees(array(
                                          'username' => $username,
                                          'password' => $password
                                          ));
      
      $session->clearChannels();

      
      if (count($employees) === 1) {

        $employee = $employees[0];
        $session->setUsername($username);
        

        return array(
                     'success' => true,
                     'username' => $employee['username'],
                     'firstName' => $employee['firstName'],
                     'lastName' => $employee['lastName'],
                     'mobileTelephone' => $employee['mobileTelephone'],
                     'homeTelephone' => $employee['homeTelephone'],
                     'address' => $employee['address'],
                     'status' => $employee['status']
                     );
      } else {
        $session->setUsername('');
      }
      return array(
                   'success' => 'false'
                   );
      
      
    }
  }
}