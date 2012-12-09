<?php

class GetReports implements Controller {
  
  public function action($args, $lazy = false) {
    
    $session = Session::getInstance();
    if (!$session->getUsername()) {
      return array(
                   'success' => 'false'
                   );
    } 
    $em = ReportMapper::getInstance();

    $filter = array();
    $order = array('datetime DESC');
    return $em->getReports($filter, $order, 50);
    
  }

}