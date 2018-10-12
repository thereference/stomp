<?php

namespace Drupal\stomp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Entity\Webform;

/**
 * Class StompAdminConfigForm.
 *
 * @package Drupal\stomp\Form
 */
class StompAdminConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stomp_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'stomp.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('stomp.settings');

    $brokers = $config->get('brokers');
    $form['brokers'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Brokers'),
      '#description' => $this->t('Example: @broker. Enter one value per line.', ['@broker' => 'tcp://127.0.0.1:61613']),
      '#default_value' => $brokers ? implode("\n\r", $brokers) : '',
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('stomp.settings');
    $brokers_to_array = explode("\n\r", $form_state->getValue('brokers'));
    $config->set('brokers', $brokers_to_array);
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
