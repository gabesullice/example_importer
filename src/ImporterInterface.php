<?php

namespace Drupal\example_importer;

interface ImporterInterface {

  /**
   * Gets the status of the import.
   *
   * @return string
   */
  public function status();

  /**
   * Runs an import from the course catalog.
   */
  public function import();

  /**
   * Rollsback the coure_catalog import.
   */
  public function rollback();

  /**
   * Reset the coure_catalog import.
   */
  public function reset();

}
