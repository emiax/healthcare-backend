<?php

class RegisterEmployee {

  public function action($args) {

    $session = Session::getInstance();
    $errors = array();

    if (!isset($args->username)) {
      $erorrs[] = "username";
    }
    if (!isset($args->password)) {
      $erorrs[] = "password";
    }
    if (!isset($args->firstName)) {
      $erorrs[] = "firstName";
    }
    if (!isset($args->lastName)) {
      $erorrs[] = "lastName";
    }
    if (!isset($args->mobileTelephone)) {
      $args->mobileTelephone = null;
    }
    if (!isset($args->homeTelephone)) {
      $args->homeTelephone = null;
    }
    if (!isset($args->address)) {
      $args->address = null;
    }


    if (!empty($errors)) {
      return array(
                   'success' => false,
                   'errors' => $errors
                   );
    }

    $username = $args->username;
    $password = $args->password;
    $firstName = $args->firstName;
    $lastName = $args->lastName;
    $mobileTelephone = $args->mobileTelephone;
    $homeTelephone = $args->homeTelephone;
    $address = $args->address;

    $mapper = EmployeeMapper::getInstance();
    try {
      $inserted = $mapper->insertEmployee(array(
                                                'username' => $username,
                                                'password' => $password,
                                                'firstName' => $firstName,
                                                'lastName' => $lastName,
                                                'mobileTelephone' => $mobileTelephone,
                                                'homeTelephone' => $homeTelephone,
                                                'address' => $address
                                                ));
    } catch (Exception $e) {
      return array('success' => false);
    }
    
    if ($inserted) {
      return array('success' => true);
    } else {
      return array('success' => false);
    }
  }
}