<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'dbConnection.php';
include 'model/employeeMapper.php';
include 'model/visitMapper.php';
include 'model/reportMapper.php';

include 'session.php';


include 'controller/controller.php';
include 'controller/logIn.php';
include 'controller/usersOnline.php';
include 'controller/getEmployees.php';
include 'controller/getSchedule.php';
include 'controller/addReport.php';
include 'controller/getReports.php';


/*
 * Setup session and channel
*/

$channelId = null;
if (isset($_REQUEST['channel'])) {
  $channelId = $_REQUEST['channel'];
}

$session = Session::start($channelId);


/*
 * Sample input:
 * channel = 71,
 * get = {
 *  "1": { "action": "login",
 *    "args": {"username": "johndoe", "password": "trollolloll"}
 *  }, 
 *  "2": { "action": "doSomethingElse",
 *    "args": {"foo": "baz"}
 *  }
 *  "3": { "action": "getSchedule",
 *    "args": {"day": 10, "month": 2, "year": 2012}
 *    "lazy": true
 *   }
 * }
 *
 */


$requests = isset($_REQUEST['request']) ? (array) json_decode($_REQUEST['request']) : array();
$responses = array();


/*
 * Make a request to a controller.
 * if lazy is true, the controller may refuse to carry out the whole request
 * if it realizes that there has been no interesting state change since last time the request was performed.
 */
function makeRequest($req, $lazy) {
  $className = ucfirst($req->action);
  if (class_exists($className)) {
    $c = new $className();
    return $c->action($req->args, $lazy);
  }
  die(json_encode(array("error" => "no such operation")));
}


foreach($requests as $k => $req) {
  $lazy = isset($req->lazy) && $req->lazy;
  $res = makeRequest($req, $lazy);
  if ($res) {
    if ($lazy) {
      if ($session->updateState($k, json_encode($res))) {
        $responses[$k] = $res;
      }
    } else {
      $session->updateState($k, json_encode($res));
      $responses[$k] = $res;
    }
  }
}

$json = array();
$json['channel'] = $session->getChannelId();
$json['response'] = $responses;

$session->close();

echo json_encode($json);