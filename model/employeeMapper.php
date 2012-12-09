<?php

class EmployeeMapper {

  private static $columns = array('username',
                        'firstName',
                        'lastName',
                        'password',
                        'mobileTelephone',
                        'homeTelephone',
                        'address',
                        'status',
                        'loggedIn',
                        'latestActivity');

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
      $instance = new EmployeeMapper();
    }
    return $instance;
  }

  
  /*
   * Change status of employee
   */
  public function setStatus($userId, $status) {
    $db = DbConnection::getInstance();

    switch($status) {
    case 'free':
    case 'busy':
    case 'offDuty':
      $validStatus = $status;
    default: 
      $validStatus = 'auto';
    }

    $db->query('UPDATE employee SET status = :status', array(
                                                             'status' => $validStatus
                                                             ));
  }

  public function setLoggedIn($loggedIn = true) {
    $db->query('UPDATE employee SET loggedIn = :loggedIn', !!$loggedIn);
  }

  
  public function insertEmployee($employee) {
    $employee = array_intersect_key($employee, array_flip(self::$columns));
    $db = DbConnection::getInstance();
    
    
    $username = &$employee['username'];
    $password = &$employee['password'];

    if (!isset($username) || !isset($password)) {
      throw new Exception("Could not insert employee without username or password");
    }
    $password = $this->encryptPassword($username, $password);
    

    $q = 'INSERT INTO employee ('; 
    $q .= implode(', ', array_keys($employee));
    $q .= ") VALUES (";

    $q .= implode(', ', array_map(function ($a) { return ":$a"; }, array_keys($employee)));    

    $q .= ");";
    return $db->query($q, $employee);    
  }
  
  private function encryptPassword($username, $password) {
    return sha1($username . "$5¤" . $password);
  }


  public function getEmployees($filter) {
    $filter = array_intersect_key($filter, array_flip(self::$columns));
    $db = DbConnection::getInstance();
    
    $publicColumns = array_diff(self::$columns, array('password'));

    
    $q = 'SELECT ' . implode(', ', $publicColumns) . ' FROM employee WHERE 1 ';
    
    if (isset($filter['password'])) {
      if (isset($filter['username'])) {
        $filter['password'] = $this->encryptPassword($filter['username'], $filter['password']);
      } else {
        throw new Exception("Cannnot filter by password without specifying username");
      }
    }
    
    foreach ($filter as $k => $v) {
      $q .= "AND $k = :$k ";
      $values[$k] = $v;
    }
    
    $ret = $db->query($q, $filter);
    if (is_array($ret)) {
      return $ret;
    } else {
      return array();
    }
  }

  public function getEmployee($username) {
    $employees = $this->getEmployees(array('username' => $username));
    $emp = &$employees[0];
    if (isset($employee)) {
      return $employee;
    }
  }

  public function getNotes($username) {
    $q = 'SELECT id, text from note WHERE employee = :username';
    $db = DbConnection::getInstance();
    
    return $db->query($q, array("username" => $username));
  }

  



}
