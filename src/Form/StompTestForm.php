<?php

namespace Drupal\stomp\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\HttpFoundation\Response;

class StompTestForm extends FormBase {

  protected $stomp;

  public function getFormId() {
    return 'stomp.test.form';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $form['markup'] = [
      '#markup' => $this->t('Test form for stomp.'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['connect'] = [
      '#type' => 'submit',
      '#value' => $this->t('Test connection'),
      '#submit' => [
        '::connectHandler',
      ],
    ];

    $form['actions']['write'] = [
      '#type' => 'submit',
      '#value' => $this->t('Test write'),
      '#submit' => [
        '::writeHandler',
      ],
    ];

    $form['actions']['read'] = [
      '#type' => 'submit',
      '#value' => $this->t('Test read'),
      '#submit' => [
        '::readHandler',
      ],
    ];

    return $form;

  }

  public function connectHandler(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $message = $this->connect() ? 'Connection works' : 'Connection doesn\'t work';
    $this->notify($message);
  }

  public function connect() {
    /** @var \Drupal\stomp\Service\StompService $stomp */
    $this->stomp = \Drupal::service('stomp.connection');
    $response = new Response($this->stomp->connect());
    return $response;
  }

  public function notify($message) {
    /** @var MessengerInterface $messenger */
    $messenger = \Drupal::service('messenger');
    $messenger->addMessage($message);
  }

  public function writeHandler(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $this->connect();
    $this->notify($this->stomp->write('Dit is het bericht'));
  }

  public function readHandler(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $this->connect();
    $this->notify($this->stomp->read()->getBody());
  }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}