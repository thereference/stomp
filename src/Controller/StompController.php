<?php

namespace Drupal\stomp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class StompController extends ControllerBase {

  public function connect() {
    /** @var \Drupal\stomp\Controller\StompService $stomp */
    $stomp = \Drupal::service('stomp.connection');
    $stomp->connect();

    $respone = new Response('Hallo');
    return $respone;
  }

}