<?php

namespace Drupal\example_importer\Plugin\migrate\process;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "example_image_url"
 * )
 */
class ExampleImageUrl extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!isset($value->image->raw)) return $value;
    $uri = $value->image->raw;
    $url = Url::fromUri($uri)->setAbsolute(FALSE)->toString();
    $target_dir = 'public://course_catalog';
    $filename = basename($url);
    file_prepare_directory($target_dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);
    $destination = sprintf('%s/%s', $target_dir, $filename);
    $file = system_retrieve_file($uri, $destination, TRUE, FILE_EXISTS_REPLACE);
    return ($file) ? $file->id() : NULL;
  }

}

