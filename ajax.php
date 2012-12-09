<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'dbConnection.php';
include 'model/employeeMapper.php';
include 'model/patientMapper.php';
include 'model/visitMapper.php';
include 'model/reportMapper.php';

include 'session.php';
include 'request.php';

include 'controller/controller.php';
include 'controller/logIn.php';
include 'controller/logOut.php';

include 'controller/getStatus.php';
include 'controller/usersOnline.php';
include 'controller/getEmployees.php';
include 'controller/getSchedule.php';
include 'controller/addReport.php';
include 'controller/getReports.php';


/*
 * Sample input:
 * channel = 71,
 * request = {
 * Setup session and channel
*/


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


$req = new Request($_REQUEST);
echo $req->respond();
$req->close();
