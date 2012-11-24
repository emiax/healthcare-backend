<?php

class Session {
  
  private static $instance = null;

  private $channelId = null;

  private $channels;

  private $nextChannelId = 0;


  /*
   * Create a new session. Happens when a new broswer is connecting (tabs may share session)
   */
  private function __construct() {
    $this->channels = array();
    $this->nextChannelId = 0;
  }
  

  /*
   * Return session object
   */
  public static function start($channelId) {
    session_start();

    $instance = self::$instance;

    if (!$instance) {
      if (isset($_SESSION['data'])) {
          $instance = unserialize($_SESSION['data']);
        }
    }

    if (!$instance) {
      $instance = new Session();
    }

    $instance->startChannel($channelId);

    self::$instance = $instance;
    return $instance;
  }


  /*
   * Creates a new channel or uses the channel specified in $_REQUEST['channel']
   */
  private function startChannel($id) {
    $this->channelId = null;
    $channelId = &$this->channelId;
    $channels = &$this->channels;

    if (isset($id)) {
      $channelId = $id;
    }

    if (!is_numeric($channelId)) {
      $channelId = $this->nextChannelId++;      
    }
    
    if (!isset($channels[$channelId])) {
      $channel = &$channels[$channelId];
      $channel = array(
                       'subscriptions' => array(),
                       'stateHashes' => array()
                       );
      
    }
  }


  /*
   * Set subscriptions
   */
  public function setSubscriptions($subs) {
    $this->channels[$this->channelId]['subscriptions'] = array_unique($subs);
  }


  /*
   * Get subscriptions
   */
  public function getSubscriptions() {
    $channel = &$this->channels[$this->channelId];
    if (isset($channel['subscriptions'])) {
      return $channel['subscriptions'];
    } else {
      return array();
    }
  }


  /*
   * Update state
   */
  public function updateState($key, $newValue) {
    $channel = &$this->channels[$this->channelId];
    if (isset($channel['stateHashes'])) {
      $hashes = &$channel['stateHashes'];

      if (!isset($hashes[$key])) {
        $hashes[$key] = null;
      }

      $hash = &$hashes[$key];
      $newHash = md5($newValue);
      
      if ($hash !== $newHash) {
        $hash = $newHash;
        return true;
      } else {
        return false;
      }
    } else {
      session_unset();
      return true;
    }
  }
  

  /*
   * Return channel id
   */
  public function getChannelId() {
    return $this->channelId;
  }

  
  /*
   * Store session variables in $_SESSION
   */
  public function close() {
    $_SESSION['data'] = serialize($this);
    session_write_close();
  }


  public static function getInstance() {
    return self::$instance;
  }

}

