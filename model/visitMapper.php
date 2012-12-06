<?php

class VisitMapper {

  /*
   * Singleton instance
   */
  private static $instance;

  /*
   * Private constructor
   */
  private function __construct() {  }


  /*
   * Return singleton instance
   */
  public static function getInstance() {
    $instance = &self::$instance;
    if (!$instance) {
      $instance = new VisitMapper();
    }
    return $instance;
  }


  public function getSchedule($userName, $filter) {
    
    $date = $filter['date'];
    $future = isset($filter['future']) && $filter['future'];
      
    $db = DbConnection::getInstance();
    $q = 'SELECT v.id AS id,
                 v.start AS start,
                 v.end AS end,
                 v.patient AS patient,
                 p.firstName AS firstName,
                 p.lastName AS lastName,
                 p.address AS address,
                 (SELECT COUNT(*) FROM visit_report r WHERE r.visit = v.id) AS reported
          FROM visit v
          JOIN _patient p ON v.patient = p.id

          WHERE v.employee = :userName
          AND DATE(v.start) = :date';
    
    $params = array(
                    'userName' => $userName,
                    'date' => $db->sqlDate($date)
                    );
    
    if ($future) {
      $q .= ' AND v.end > :now';
      $params['now'] = $db->sqlDatetime(mktime());
    }
    
    $q .= ';';

    $result = $db->query($q, $params);
    if (is_array($result)) {
      return $result;
    } else {
      return array();
    }
  }

}