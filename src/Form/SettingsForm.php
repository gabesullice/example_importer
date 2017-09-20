<?php

namespace Drupal\example_importer\Form;

use Drupal\example_importer\ImporterInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a form that configures forms module settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The course catalog importer service.
   *
   * @var \Drupal\example_importer\ImporterInterface
   */
  protected $importer;

  /**
   * {@inheritdoc}
   */
  public function __construct(ImporterInterface $importer) {
    $this->importer = $importer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('example_importer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'example_importer_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'example_edx_api.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('example_edx_api.settings');

    $form['status'] = [
      '#prefix' => '<strong>Import Status:</strong> ',
      '#markup' => $this->importer->status(),
    ];

    $form['base_uri'] = [
      '#title' => 'Open Edx API Base URL',
      '#description' => 'Example: https://api.example.org',
      '#type' => 'textfield',
      '#default_value' => $config->get('base_uri'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = $this->config('example_edx_api.settings');

    if ($url = $form_state->getValue('base_uri')) {
      $config->set('base_uri', $url)->save();
    }
  }

}
