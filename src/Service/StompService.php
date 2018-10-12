<?php

namespace Drupal\stomp\Service;
use Drupal\Core\Config\ConfigFactoryInterface;
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
   * Our SimpleStomp instance.
   * @var \Stomp\SimpleStomp
   */
  private $stomp;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * StompController constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The Config factory.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
    $brokers = $this->configFactory->get('stomp.config')->get('brokers');
    $this->stomp = new StatefulStomp(new Client($brokers[0]));
  }

  /**
   * Connect to a STOMP queue.
   */
  public function connect() {
    return $this->stomp ? 'OK' : 'Something went wrong when connecting.';
  }

  public function read(){
    $this->stomp->subscribe('/queue/' . 'Drupal');
    return $this->stomp->read();
  }

  public function write($message){
    $message = new Message($message);
    return $this->stomp->send('Drupal', $message) ? 'OK' : 'Something went wrong.';
  }
}
