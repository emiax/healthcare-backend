<?php

class Request {

  private $requests;

  private $db;

  private $session;

  
  /*
   * Constructor, input is $_REQUEST variable
   */
  public function __construct($params) {

    $channelId = &$params['channel'];
    if (!isset($channelId)) {
      $channelId = 0;
    }

    $requests = array();
    $reqString = &$params['request'];
    

    if (isset($reqString)) {
      $requests = json_decode($reqString);
      if ($requests === null) {
        $this->throwError("malformed json in request");
      } else {
        $this->requests = $requests;
      }
    }
    
    $this->session = Session::start($channelId);
  }

  
  //////////////////////////////////////////////////////////////
  // Public methods
  /////////////////////////////////////////////////////////////


  /*
   * Produce responce
   */
  public function respond() {
    $responses = array();

    // Perform the requests in reverse order to make sure previous subscriptions are handled after explicit request
    $keys = array_keys((array) $this->requests);

    foreach($keys as $k) {
      $req = $this->requests->$k;
      $lazy = isset($req->lazy) && $req->lazy;
      $action = &$req->action;
      $args = &$req->args;
      
      // Perform some validation
      if (!isset($action)) {
        $this->throwError("All requests must have a specified action");
      }
      if (!isset($args)) {
        $args = array();
      }
      
      // Perform request and store state in session
      $res = $this->performRequest($action, $args, $lazy);
      if ($res && $this->session->updateState($k, json_encode($res)) || !$lazy) {
        $responses[$k] = $res;
      }
    }

    $json = array();
    $session = $this->session;
    $json['channel'] = $session->getChannelId();
    $json['response'] = $responses;
    return json_encode($json);
  }


  /*
   * Done and done!
   */
  public function close() {
    $this->session->close();
  }

  
  //////////////////////////////////////////////////////////////
  // Private methods
  /////////////////////////////////////////////////////////////


  /*
   * Perform request
   */
  private function performRequest($action, $args, $lazy) {
      $className = ucfirst($action);
      $rc = null;
      try {
        $rc = new ReflectionClass($className);
      } catch (Exception $e) {
        $this->throwError("No such operation: $action");
      }
      
      if (!$rc->implementsInterface("Controller")) {
        $this->throwError("No such operation: $action");
      }

      $controller = $rc->newInstance();
      return $controller->action($args, $lazy);
  }

  
  /*
   * Return a json formatted error to the browser
   */
  private function throwError($str) {
    $data = array(
                  'success' => 'false',
                  'error' => $str,
                  );
    if ($this->session) {
      $data['channel'] = $this->session->getChannelId();
    }
    
    die(json_encode($data));
  }
}
