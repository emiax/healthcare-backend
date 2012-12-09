<?php

class PatientMapper {

  private static $columns = array('id',
                                  'firstName',
                                  'lastName',
                                  'address',
                                  'personalNumber',
                                  'weight',
                                  'height',
                                  'mobileTelephone',
                                  'homeTelephone',
                                  'familyTelephone');

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
      $instance = new PatientMapper();
    }
    return $instance;
  }

  public function insertPatient($patient) {
    $employee = array_intersect_key($patient, array_flip(self::$columns));
    $db = DbConnection::getInstance();
    
    $q = 'INSERT INTO patient ('; 
    $q .= implode(', ', array_keys($patient));
    $q .= ") VALUES (";

    $q .= implode(', ', array_map(function ($a) { return ":$a"; }, array_keys($patient)));    

    $q .= ");";
    return $db->query($q, $patient);    
  }
  
  public function getAgeFromPersonalNumber($personalNumber) {
    $year = substr($personalNumber, 0, 4);
    $month = substr($personalNumber, 4, 2);
    $day = substr($personalNumber, 6, 2);

    $birth = new DateTime("$year-$month-$day");
    $now = new DateTime();

    return $now->diff($birth)->y;
  }



}
