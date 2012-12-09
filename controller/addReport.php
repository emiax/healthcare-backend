<?php

class AddReport implements Controller {
  
  public function action($args, $lazy = false) {
    $session = Session::getInstance();
    $rm = ReportMapper::getInstance();
    
    $status = &$args->status;
    $datetime = mktime();

    $patient = &$args->patient;
    $employee = $session->getUsername();
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
      try {
        $rm->addVisitReport($status, $text, $datetime, $visit);
        return array('success' => true);
      } catch (Exception $e) {
        return array('success' => false);
      }
    } elseif (isset($employee) && isset($patient)) {
      try {
        $rm->addReport($status, $text, $datetime, $patient, $employee);
        return array('success' => true);
      } catch (Exception $e) {
        return array('success' => false);
      }
    }
    return array('success' => false);    
  }

}
