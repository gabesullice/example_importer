<?php

namespace Drupal\example_importer\Plugin\migrate\process;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\taxonomy\TermStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @MigrateProcessPlugin(
 *   id = "taxonomy_term_simple"
 * )
 */
class TaxonomyTermSimple extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The taxonomy term storage service.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TermStorageInterface $term_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->termStorage = $term_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('entity_type.manager')->getStorage('taxonomy_term')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return ($this->getConfigValue('create_if_not_exists'))
      ? $this->termFromName($value)
      : $this->lookupTermByName($value);
  }

  /**
   * Get a taxonomy term from a given term name.
   *
   * Looks up a term by name first. If one doesn't exist, then we create it.
   */
  protected function termFromName($name) {
    return $this->lookupTermByName($name) ?? $this->createTermFromName($name);
  }

  /**
   * Creates a new taxonomy term from the given name.
   *
   * Notice that the term uses the vocabulary id given by the migration config.
   */
  protected function createTermFromName($name) {
    $term = $this->termStorage->create([
      'vid' => $this->getConfigValue('vocabulary'),
      'name' => $name,
    ]);
    $term->save();
    return $term;
  }

  /**
   * Looks up a taxonomy term from the given name.
   *
   * Notice that the term uses the vocabulary id given by the migration config.
   */
  protected function lookupTermByName($name) {
    $terms = $this->termStorage->loadByProperties([
      'vid' => $this->getConfigValue('vocabulary'),
      'name' => $name,
    ]);

    // Just return the first instance of a term if multiple exist.
    return (!empty($terms)) ? reset($terms) : NULL;
  }

  /**
   * Simple helper to retrieve a configuration value.
   */
  protected function getConfigValue($key) {
    return $this->configuration[$key] ?? NULL;
  }

}

