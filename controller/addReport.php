<?php

class AddReport implements Controller {
  
  public function action($args, $lazy = false) {
    $session = Session::getInstance();
    $rm = ReportMapper::getInstance();
    
    $status = &$args->status;
    $datetime = mktime();

    $patient = &$args->patient;
    $employee = &$args->employee;
    $visit = &$args->visit;

    $text = &$args->text;

    if (!isset($status)) {
      $status = 'neutral';
    }
    
    if (!$session->getUsername()) {
      return array(
                   'succuss' => false
                   );
    }
    if (isset($visit)) {
      if ($rm->addVisitReport($status, $text, $datetime, $visit)) {
        return array('success' => true);
      }
    } elseif (isset($employee) && isset($patient)) {
      if ($rm->addReport($status, $text, $datetime, $patient, $employee)) {
        return array('success' => true);
      }
    }
    
    return array('success' => false);
  }

}
