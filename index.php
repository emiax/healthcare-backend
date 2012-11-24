<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'session.php';
include 'loginController.php';
include 'usersOnlineController.php';


$channelId = null;
if (isset($_REQUEST['channel'])) {
  $channelId = $_REQUEST['channel'];
}

$session = Session::start($channelId);



/*
 * Sample input:
 *
 * get = [
 *  { "action": "login",
 *    "args": {"username": "johndoe", "password": "trollolloll"}
 *  }, 
 *  { "action": "doSomethingElse",
 *    "args": {"foo": "baz"}
 *  }
 * ],
 * subscribe = [
 *  { "action": "getSchedule",
 *    "args": {"day": 10, "month": 2, "year": 2012}
 *  }
 * ]
 *
 */


//$_REQUEST['subscribe'] = '[{"action":"getOnlineUsers","args":{}}]';

$once = isset($_REQUEST['get']) ? json_decode($_REQUEST['get']) : array();
$newSubscriptions = isset($_REQUEST['subscribe']) ? json_decode($_REQUEST['subscribe']) : null;

$forcedRequests = array();
$lazyRequests = array();

if ($newSubscriptions) {
  $session->setSubscriptions($newSubscriptions);
  $forcedRequests = array_merge($once, $newSubscriptions);
} else {
  $forcedRequests = $once;
  $lazyRequests = array_diff($session->getSubscriptions(), $forcedRequests);
}

//print_r($forcedRequests);
//print_r($lazyRequests);

$responses = array();


/*
 * Makes a request to a controller.
 * if forceResponse is false, the controller may refuse to carry out the whole request
 * if it realizes that there has been no interesting state change since last time the request was performed.
 */
function makeRequest($req, $forceResponse) {

  $c = null;
  switch($req->action) {
  case 'logIn': 
    $c = new LoginController();
    break;
  case 'getOnlineUsers':
    $c = new UsersOnlineController();
    break;
  }
  if ($c) {
    return $c->output($req->args, $forceResponse);
  }
}

foreach($forcedRequests as $k => $req) {
  $res = makeRequest($req, true);
  if ($res) {
    $session->updateState(json_encode($req), json_encode($res));
    $responses[$k] = $res;
  }
}

foreach($lazyRequests as $k => $req) {
  $res = makeRequest($req, false);
  if ($res && $session->updateState(json_encode($req), json_encode($res))) {
    $responses[$k] = $res;
  }
}

$responses['channel'] = $session->getChannelId();
$session->close();

echo json_encode($responses);