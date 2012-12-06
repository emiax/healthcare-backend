<?php

class GetStatus implements Controller {
  
	public function action($args, $lazy = false) {
		$session = Session::getInstance();
		$userName = $session->getUserName();

		return array(
			'username' => $userName,
			'success' => true
		);
	}
	
}

?>