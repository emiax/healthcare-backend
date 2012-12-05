<?php

class GetReports {
  
  public function action($args) {
    
    $session = Session::getInstance();
    if (!$session->getUserName()) {
      return array(
                   'success' => 'false'
                   );
    } 
    $em = ReportMapper::getInstance();

    $filter = array();
    $order = array();
    return $em->getReports($filter, $order);
    
  }

}