<?php

class UsersOnline {
  
  public function action($args, $lazy = false) {
    if (isset($args->filter)) {
      return array('foo' => '10');
    }
    return array('foo' => '42');
  }

}