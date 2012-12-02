<?php

class ReportMapper {

  /*
   * Singleton instance
   */
  private static $instance;

  /*
   * Private constructor
   */
  private function __construct() {}


  /*
   * Return singleton instance
   */
  public static function getInstance() {
    $instance = &self::$instance;
    if (!$instance) {
      $instance = new ReportMapper();
    }
    return $instance;
  }


  public function addVisitReport($status, $text, $datetime, $visit) {
    $db = DbConnection::getInstance();
    
    $q = 'INSERT INTO visit_report SET visit = :visit,
                                         status = :status,
                                         text = :text,
                                         datetime = :datetime;';
    $res = null;
    try {
      $res = $db->query($q,
                        array('visit' => $visit,
                              'status' => $status,
                              'text' => $text,
                              'datetime' => $db->sqlDatetime($datetime)
                              )
                        );
    } catch(Exception $e) {
       return false;
    }
    return true;
  }
    
  public function addReport($status, $text, $datetime, $patient, $employee) {
    
    $q = 'INSERT INTO report SET datetime = :datetime,
                                 patient = :patient,
                                 employee = :employee,
                                 status = :status,
                                 text = :text';
    $res = null;
    try {
      $db->query($q, array('employee' => $employee,
                           'patient' => $patient,
                           'status' => $status,
                           'text' => $text,
                           'datetime' => $db->sqlDatetime($datetime)
                           )
                 );
    } catch(Exception $e) {
      return false;
    }
    return $res;
  }
  

  /*
   * Return reports fullfilling a $filter, and sorted accordingly to $sort
   */
  public function getReports($filter, $order = null) {
    
    $q = 'SELECT id,
                 patient,
                 employee,
                 status,
                 text,
                 datetime,
                 "general" AS type
          FROM report';

    $r = 'SELECT r.visit,
                 v.patient,
                 v.employee,
                 r.status,
                 r.text,
                 r.datetime
                 "visit" AS type
          FROM visit_report r
          JOIN visit v ON r.visit = v.id';

    if (isset($filter['after'])) {
      $after = $db->sqlTime($filter['after']);
    }
    if (isset($filter['before'])) {
      $before = $db->sqlTime($filter['before']);
    }
    
    $patient = &$filter['patient'];
    $status = &$filter['status'];
    $employee = &$filter['employee'];

    $params = array();
    
    $qWhere = ' WHERE 1 ';
    $rWhere = ' WHERE 1 ';

    if (isset($after)) {
      $qWhere .= 'AND datetime > :after ';
      $rWhere .= 'AND v.end > :after ';
      $params['after'] = $after;
    }
    
    if (isset($before)) {
      $qWhere .= 'AND datetime < :before ';
      $rWhere .= 'AND v.start < :before ';
      $params['before'] = $before;
    }
    
    if (isset($employee)) {
      $qWhere .= 'AND employee = :employee ';
      $rWhere .= 'AND v.employee = :employee';
      $parms['employee'] = $employee;

    }

    if (isset($patient)) {
      $qWhere .= 'AND patient = :patient ';
      $rWhere .= 'AND v.patient = :patient ';
      $parms['patient'] = $patient;
    }
    
    if (isset($status)) {
      $qWhere .= 'AND status = :status ';
      $rWhere .= 'AND r.status = :status ';
      $params['status'] = $status;
    }

    $query = "$q $qWhere UNION $r $rWhere"; 

    if (isset($order)) {
      $query .= 'ORDER BY ' . implode(', ', $order) . ";";
    } else {
      $query .= 'ORDER BY time';
    }

    return $db->query($q, $params);
  }

}
