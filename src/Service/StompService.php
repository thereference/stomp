<?php

namespace Drupal\stomp\Controller;
use Drupal\Core\Config\ConfigFactoryInterface;
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
    $this->getStompParams();
  }

  /**
   * Get the parameters for out STOMP connection.
   */
  protected function getStompParams() {
    $brokers = $this->configFactory->get('stomp.config')->get('brokers');
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
