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
  public function getReports($filter, $order = null, $limit = 50) {
    $db = DbConnection::getInstance();
    
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
                 r.datetime,
                 "visit" AS type
          FROM visit_report r
          JOIN visit v ON r.visit = v.id';

    if (isset($filter['after'])) {
      $after = $db->sqlDateTime($filter['after']);
    }
    if (isset($filter['before'])) {
      $before = $db->sqlDateTime($filter['before']);
    }
    
    $patient = &$filter['patient'];
    $status = &$filter['status'];
    $employee = &$filter['employee'];

    $params = array();
    
    $qWhere = 'WHERE 1 ';
    $rWhere = 'WHERE 1 ';
    
    if (isset($after)) {
      $qWhere .= 'AND datetime > :qAfter ';
      $rWhere .= 'AND v.end > :rAfter ';
      $params['rAfter'] = $params['qAfter'] = $after;
    }
    
    if (isset($before)) {
      $qWhere .= 'AND datetime < :qBefore ';
      $rWhere .= 'AND v.start < :rBefore ';
      $params['rBefore'] = $params['qBefore'] = $before;
    }
    
    if (isset($employee)) {
      $qWhere .= 'AND employee = :qEmployee ';
      $rWhere .= 'AND v.employee = :qEmployee';
      $params['rEmployee'] = $params['qEmployee'] = $employee;
    }

    if (isset($patient)) {
      $qWhere .= 'AND patient = :qPatient ';
      $rWhere .= 'AND v.patient = :rPatient ';
      $parms['rPatient'] = $parms['qPatient'] = $patient;

    }
    
    if (isset($status)) {
      $qWhere .= 'AND status = :qStatus ';
      $rWhere .= 'AND r.status = :rStatus ';
      $params['rStatus'] = $params['qStatus'] = $status;
      }

    $query = "($q $qWhere) UNION ($r $rWhere)"; 

    if (!empty($order)) {
      $query .= ' ORDER BY ' . implode(', ', $order) . ";";
    } else {
      $query .= ' ORDER BY datetime';
    }
    
    $limit = (int) $limit;
    if ($limit) {
      $query .= "LIMIT $limit";
    }

    $res = $db->query($query, $params);
    $structure = array();

    $res = array_reverse($res);
    foreach($res as $r => $v) {
      $k = &$structure[$r];
      $k = array(
                 'id' => $v['id'],
                 'patient' => $v['patient'],
                 'employee' => $v['employee'],
                 'status' => $v['status'],
                 'text' => $v['text'],
                 'datetime' => $db->jsonDateTime($v['datetime']),
                 'type' => $v['type']
      );
    }

    return $structure;
  }

}
