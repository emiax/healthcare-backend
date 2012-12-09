<?php

class GetStatus implements Controller {
  
	public function action($args, $lazy = false) {
		$session = Session::getInstance();
		$username = $session->getUsername();
                
                //if ($lazy && !$session->updateState('getStatusUsername', $username)) {
                //  return;
                //}

                $response = array(
                                  'success' => true,
                                  );
                
                if ($username) {
                  $em = EmployeeMapper::getInstance();
                  $employees = $em->getEmployees(array('username' => $username));
                  
                  if (count($employees) === 1) {
                    $employee = $employees[0];

                    $response['username'] = $username;
                    $response['firstName'] = $employee['firstName'];
                    $response['lastName'] = $employee['lastName'];
                    $response['mobileTelephone'] = $employee['mobileTelephone'];
                    $response['homeTelephone'] = $employee['homeTelephone'];
                    $response['address'] = $employee['address'];
                    $response['status'] = $employee['status'];

                  } else {
                    $session->setUsername('');
                  }
                }
                  
		return $response; 
	}
	
}

?>