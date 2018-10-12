<?php

namespace Drupal\stomp\Service;

use Stomp\Client;
use Stomp\SimpleStomp;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;

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
   * The active queue.
   * @var string
   */
  private $queue;

  /**
   * @param string $queue
   */
  public function setQueue($queue) {
    $this->queue = $queue;
  }

  /**
   * StompController constructor.
   */
  public function __construct() {
    $this->getStompParams();
    $this->queue = 'Drupal';
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
    /** @var \Stomp\SimpleStomp stomp */
    $this->stomp = new StatefulStomp(new Client('tcp://activemq'));
    return $this->stomp ? 'OK' : 'Something went wrong when connecting.';
  }



  public function read(){
    $this->stomp->subscribe('/queue/' . $this->queue);
    return $this->stomp->read();
  }

  public function write($message){
    $message = new Message($message);
    return $this->stomp->send($this->queue, $message) ? 'OK' : 'Something went wrong.';
  }
}
