<?php

namespace Drupal\stomp\Controller;

require __DIR__ . '/../../vendor/autoload.php';
use Drupal\Core\Controller\ControllerBase;
use Stomp\Client;
use Stomp\SimpleStomp;

class StompController extends ControllerBase {

  private $stompParams;
  private $stomp;

  public function __construct() {
    $this->getStompParams();
  }

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

  private function getStompParams() {
    //TODO set stomp params.
  }
}
