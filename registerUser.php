<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


include 'dbConnection.php';
include 'model/employeeMapper.php';
include 'controller/registerEmployee.php';
include 'session.php';


echo "Registering!";
$c = new RegisterEmployee();
print_r($c->action((object) array(
                          'userName' => 'tomfo',
                          'password' => 'test',
                          'firstName' => 'Tomas',
                          'lastName' => 'Forsyth Rosin'
                                  )));