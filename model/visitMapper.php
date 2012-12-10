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
                 v.description AS description,

                 v.patient AS patient,

                 p.firstName AS firstName,
                 p.lastName AS lastName,
                 p.profilePicture AS profilePicture,
                 p.personalNumber AS personalNumber,
                 p.weight AS weight,
                 p.height AS height,
                 p.homeTelephone AS homeTelephone,
                 p.mobileTelephone AS mobileTelephone,
                 p.familyTelephone AS familyTelephone,
                 p.address AS address,                  

                 (SELECT COUNT(*) FROM visit_report r WHERE r.visit = v.id) AS reported,
                 
                 (
                   SELECT status FROM (
                     (SELECT status, patient, datetime FROM visit_report vr JOIN visit w ON w.id = vr.visit)
                     UNION 
                     (SELECT status, patient, datetime FROM report r)
                     UNION
                     (SELECT "alert", patient, datetime FROM fall)
                   ) AS a WHERE patient = v.patient ORDER BY datetime DESC LIMIT 1
                 ) AS status
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
    
    if ($future) {
      $q .= ' AND v.end > :nowFuture';
      $params['nowFuture'] = $db->sqlDatetime(mktime());
    }

    if ($past) {
      $q .= ' AND v.start < :nowPast';
      $params['nowPast'] = $db->sqlDatetime(mktime());
    }

    
    $q .= ' ORDER BY start';
    if ($past) {
      $q .= ' DESC';
    } else {
      $q .= ' ASC';
    }
    
    // Limit number of future or past visits to 50
    if (!isset($date) && ($future || $past)) {
      $q .= ' LIMIT 50';
    }

    $q .= ';';

    $result = $db->query($q, $params);
    $structure = array();

    $pm = PatientMapper::getInstance();

    if (is_array($result)) {
      
      foreach($result as $r => $v) {
        $k = &$structure[$r];

        $k = array();
        $k['id'] = $v['id'];
        $k['start'] = $db->jsonDateTime($v['start']);
        $k['end'] = $db->jsonDateTime($v['end']);
        $k['description'] = htmlspecialchars($v['description']);

        $k['patient'] = array(
                              'id' => $v['patient'],
                              'firstName' => $v['firstName'],
                              'lastName' => $v['lastName'],
                              'profilePicture' => $v['profilePicture'],
                              'age' => $pm->getAgeFromPersonalNumber($v['personalNumber']),
                              'weight' => $v['weight'],
                              'homeTelephone' => $v['homeTelephone'],
                              'mobileTelephone' => $v['mobileTelephone'],
                              'familyTelephone' => $v['familyTelephone'],
                              'address' =>  htmlspecialchars($v['address']),
                              'status' => $v['status']
                              );
      }
      
      return $structure;
    } else {
      return array();
    }
  }

}