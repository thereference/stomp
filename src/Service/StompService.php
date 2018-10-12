<?php

namespace Drupal\stomp\Controller;
use Stomp\Client;
use Stomp\SimpleStomp;

/**
 * Class StompController
 * @package Drupal\stomp\Controller
 */
class StompService {

  /**
   * The parameters to use in our STOMP connection.
   *
   * @var array
   */
  private $stompParams;

  /**
   * Our SimpleStomp instance.
   * @var \Stomp\SimpleStomp
   */
  private $stomp;

  /**
   * StompController constructor.
   */
  public function __construct() {
    $this->getStompParams();
  }

  /**
   * Get the parameters for out STOMP connection.
   */
  protected function getStompParams() {
    //TODO set stomp params.
  }

  /**
   * Connect to a STOMP queue.
   */
  public function connect() {
    $this->stomp = new SimpleStomp(new Client('tcp://activemq'));
    var_dump($this->stomp);
    die();
  }
}
