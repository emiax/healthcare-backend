<?php

class GetSchedule implements Controller {
  
  public function action($args, $lazy = false) {
    $session = Session::getInstance();
    $username = $session->getUsername();
    
    if (!$username) {
      return array(
                   'success' => false
                   );
    }
    
    $date = null;
   
    
    $day = &$args->date->day;
    $month = &$args->date->month;
    $year = &$args->date->year;
    
    $relativeDay = &$args->relDate->day;
    $relativeMonth = &$args->relDate->month;
    $relativeYear = &$args->relDate->year;
    
    if (isset($day) || isset($month) || isset($year)) {
      $day = isset($day) ? $day : date('j');
      $month = isset($month) ? $month : date('n');
      $year = isset($year) ? $year : date('Y');
      //$date = mktime(date("H"), date("i"), date("s"), $month, $day, $year);
    }
    
    if (isset($relativeDay) || isset($relativeMonth) || isset($relativeYear)) {
      $day = isset($day) ? $day + $relativeDay : isset($relativeDay) ? date('j') + $relativeDay : date('j');
      $month = isset($month) ? $month + $relativeMonth : isset($relativeMonth) ? date('n') + $relativeMonth : date('n');
      $year = isset($year) ? $year + $relativeYear : isset($relativeYear) ? date('Y') + $relativeYear : date('Y');
      //$date = mktime(date("H"), date("i"), date("s"), $month, $day, $year);
    }

    $future = isset($args->future) && $args->future;
    $past = isset($args->past) && $args->past;
    

    
    $filter = array();    
    if ($future) {
      $filter['future'] = true;
    } elseif ($past) {
      $filter['past'] = true;
    }
    if ($date) {
      $filter['date'] = $date;
    }
    
    //    print_r($filter);
    
    if (empty($filter)) {
      $filter['date'] = mktime();
    }

    //    print_r($filter);

    if ($username) {
      $vm = VisitMapper::getInstance();
      return $vm->getSchedule($username, $filter);
    }
    
  }

}