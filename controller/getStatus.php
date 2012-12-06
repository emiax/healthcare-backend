<?php

class GetStatus {
  
  public function action($args) {
    $session = Session::getInstance();
    $userName = $session->getUserName();
	
	return array(
		'username' => $userName,
		'success' => true;
	);
	
}

?>