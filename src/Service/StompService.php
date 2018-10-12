<?php

namespace Drupal\stomp\Controller;

use Drupal\Core\Controller\ControllerBase;
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
   *
   * @var SimpleStomp\
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
    $connection = NULL;

    if (is_array($this->stompParams['brokers']) && count($this->stompParams['brokers']) > 1) {
      // multiple brokers specified so build up failover URL
      $connection = 'failover://(';
      $connection .= implode(',', $this->stompParams['brokers']);
      $connection .= '?randomize=' . (isset($this->stompParams['randomize']) ? $this->stompParams['randomize'] : 'false');
    }
    elseif (is_array($this->stompParams['brokers']) && count($this->stompParams['brokers']) == 1) {
      $connection = $this->stompParams['brokers'][0];
    }

    $this->stomp = new SimpleStomp(new Client('tcp://localhost:61613'));

    // Add clientId to make it a durable topic subscriber.
    if (!empty($this->stompParams['clientId'])) {
      $this->stomp->clientId = $this->stompParams['clientId'];
    }

    $user = NULL;
    $pass = NULL;

    // Check if we've got authentication credentials defined for this queue.
    if (!empty($this->stompParams['credentials']['user'])) {
      $user = $this->stompParams['credentials']['user'];
    }
    if (!empty($this->stompParams['credentials']['pass'])) {
      $pass = $this->stompParams['credentials']['pass'];
    }
  }
}
