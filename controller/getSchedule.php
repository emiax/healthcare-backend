<?php

class GetSchedule {
  
  public function action($args) {
    $session = Session::getInstance();
    $userName = $session->getUserName();
    
    if (!$userName) {
      return array(
                   'success' => false
                   );
    }

    $day = &$args->date->day;
    $month = &$args->date->month;
    $year = &$args->date->year;
    
    $relativeDay = &$args->relDate->day;
    $relativeMonth = &$args->relDate->month;
    $relativeYear = &$args->relDate->year;

    if (!isset($day) || !isset($month) || !isset($year)) {
      if (!isset($relativeDay)) {
        $relativeDay = 0;
      }
      if (!isset($relativeMonth)) {
        $relativeMonth = 0;
      }
      if (!isset($relativeYear)) {
        $relativeYear = 0;
      }
      $day = date("j") + $relativeDay;
      $month = date("n") + $relativeMonth;
      $year = date("Y") + $relativeYear;
    }

    $future = false;
    if ($day == date('j') && $month == date('n') && $year == date('Y')) {
      if (isset($args->future) && $args->future) {
        $future = true;
      }
    }
    


    $date = mktime(date("H"), date("i"), date("s"), $day, $month, $year);
    
    $filter = array(
                    'date' => $date,
                    'future' => $future
                    );
    
    if ($userName) {
      $vm = VisitMapper::getInstance();
      return $vm->getSchedule($userName, $filter);
    }
    
  }

}