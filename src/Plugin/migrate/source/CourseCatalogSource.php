<?php

namespace Drupal\example_importer\Plugin\migrate\source;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\example_edx_api\CourseDataInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @MigrateSource(
 *   id = "course_catalog"
 * )
 */
class CourseCatalogSource extends SourcePluginBase implements ContainerFactoryPluginInterface {

  /**
   * The CourseCatalog Data provider.
   *
   * @var \Drupal\example_edx_api\CourseDataInterface
   */
  protected $dataProvider;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, CourseDataInterface $data_provider) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
    $this->dataProvider = $data_provider;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('example_edx_api.course_data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return array(
      'name' => t('Name'),
      'type' => t('Bundle'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    return $this->dataProvider->all();
  }

  /**
   * Allows class to decide how it will react when it is treated like a string.
   */
  public function __toString() {
    return 'Course Catalog Migration Source Plugin';
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['id'] = [
      'type' => 'string',
    ];
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return -1;
  }

}
