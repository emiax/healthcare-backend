<?php

class DbConnection {

  /*
   * Configuration
   */
  private $hostName = '';

  private $userName = '';

  private $databaseName = '';
  
  private $password = '';


  /*
   * Singleton instance
   */
  
  private static $instance = null;


  /*
   * PDO
   */

  private $pdo = null;


  /*
   * Private constructor
   */
  private function __construct() {
    
    $config = json_decode(file_get_contents('config.json'));

    $this->pdo = new PDO('mysql:host=' . $config->hostName .
                         ';dbname=' . $config->databaseName ,
                         $config->userName,
                         $config->password);

    $this->query("SET NAMES 'utf8';", array());
  }


  /*
   * Return instance
   */
  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new DbConnection();
    }
    return self::$instance;
  }


  /*
   * SQL formatted date
   */
  public function sqlDate($epoch) {
    return date("Y-m-d", $epoch);
  }


  /*
   * SQL formatted datetime
   */
  public function sqlDateTime($input) {
    if (is_object($input) && isset($input->year) && isset($input->month) && isset($input->day)
        && isset($input->hour) && isset($input->minute) && isset($input->second)) {
      $epoch= mktime($input->hour, $input->minute, $input->second, $input->month, $input->day, $input->year);
    } else {
      $epoch = $input;
    }
    
    return date("Y-m-d H:i:s", $epoch);
  }

  
  public function jsonDateTime($sqlDateTime) {
    return array(
                 'year' => (int) substr($sqlDateTime, 0, 4),
                 'month' => (int) substr($sqlDateTime, 5, 2),
                 'day' => (int) substr($sqlDateTime, 8, 2),
                 
                 'hour' => (int) substr($sqlDateTime, 11, 2),
                 'minute' => (int) substr($sqlDateTime, 14, 2),
                 'second' => (int) substr($sqlDateTime, 17, 2)
                 );
  }


  /*
   * Query the database
   * Forward all arguments to the query method of the PDO
   */
  public function query($statement, $parameters) {
    //        die('<pre>' . print_r(debug_backtrace(), true) . "</pre>");
    //die('<pre>' . $statement . "</pre>");
    $pdoStatement = $this->pdo->prepare($statement);
    if (!is_array($parameters)) {
      $parameters = slice(function_get_arguments(), 1);
    }
    
    $paramsWithColons = array();
    foreach ($parameters as $k => $v) {
      $paramsWithColons[":$k"] = "$v";
    }

    $executed = $pdoStatement->execute($paramsWithColons);
    if ($executed) {
      $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } else {
      $debug = array(
                     //  'preQuery' => $statement,
                     'parameters' => $parameters,
                     'query' => str_replace(array_keys($paramsWithColons), array_values($paramsWithColons), $statement),
                     'error' => print_r($pdoStatement->errorInfo(), true)
                     );
      
      throw new Exception(print_r($debug, true));
    }
  }

}

?>