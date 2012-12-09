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


  public function getSchedule($username, $filter) {
    
    $date = &$filter['date'];
    $future = isset($filter['future']) && $filter['future'];
    $past = isset($filter['past']) && $filter['past'];
      
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

          WHERE v.employee = :username';
    
    $params = array(
                    'username' => $username,
                    );

    if (isset($date)) {
      $q .= ' AND DATE(v.start) = :date';
      $params['date'] = $db->sqlDate($date);
    }
    
    echo $params['date'];

    
    if ($future) {
      $q .= ' AND v.end > :nowFuture';
      $params['nowFuture'] = $db->sqlDatetime(mktime());
    }

    if ($past) {
      $q .= ' AND v.start < :nowPast';
      $params['nowPast'] = $db->sqlDatetime(mktime());
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