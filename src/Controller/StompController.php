<?php

namespace Drupal\stomp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class StompController extends ControllerBase {

  private $stomp;

  public function connect() {
    /** @var \Drupal\stomp\Service\StompService $stomp */
    $this->stomp = \Drupal::service('stomp.connection');
    $response = new Response($this->stomp->connect());
    return $response;
  }

  public function write() {
    $this->connect();
    $response = new Response($this->stomp->write('Dit is het bericht'));
    return $response;
  }
  public function read() {
    $this->connect();
    $response = new Response($this->stomp->read());
    return $response;

  }

}