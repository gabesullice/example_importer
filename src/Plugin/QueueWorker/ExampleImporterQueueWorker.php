<?php

namespace Drupal\example_importer\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\example_importer\ImporterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "example_importer",
 *   title = @Translation("Course Catalog Importer"),
 *   cron = {
 *     "time" = 30,
 *   },
 * )
 */
class ExampleImporterQueueWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The Course Catalog importer service.
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
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($container->get('example_importer'));
  }

  public function processItem($item) {
    $this->importer->import();
  }

}
