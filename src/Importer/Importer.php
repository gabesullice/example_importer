<?php

namespace Drupal\example_importer\Importer;

use Drupal\example_importer\ImporterInterface;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateMessage;

class Importer implements ImporterInterface {

  /**
   * The migration manager
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManagerInterface
   */
  protected $migrateManager;

  /**
   * The migration for the importer to run.
   *
   * @var string
   */
  protected $migration;

  /**
   * {@inheritdoc}
   */
  public function __construct(MigrationPluginManagerInterface $migration_manager) {
    $this->migrateManager = $migration_manager;
  }

  public function setMigration($migration) {
    $this->migration = $migration;
  }

  /**
   * {@inheritdoc}
   */
  public function status() {
    return $this->getMigration()->getStatusLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function import() {
    $this->doImport($this->getMigrateExecutable());
    return $this;
  }

  /**
    * {@inheritdoc}
   */
  public function rollback() {
    $this->doRollback($this->getMigrateExecutable());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function reset() {
    $this->doReset($this->getMigration());
    return $this;
  }

  protected function doImport(MigrateExecutableInterface $executable) {
    return $executable->import();
  }

  protected function doRollback(MigrateExecutableInterface $executable) {
    return $executable->rollback();
  }

  protected function doReset(MigrationInterface $migration) {
    return $migration->setStatus(MigrationInterface::STATUS_IDLE);
  }

  protected function getMigrateExecutable() {
    return $this->getExecutable($this->getMigration());
  }

  protected function getExecutable(MigrationInterface $migration) {
    $message = new MigrateMessage('Courses Imported');
    return new MigrateExecutable($migration, $message);
  }

  protected function getMigration() {
    return $this->migrateManager->createInstance($this->migration);
  }

}
