<?php

namespace Drupal\example_importer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\example_importer\ImporterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImportController extends ControllerBase {

  /**
   * The course catalog importer service.
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

  public function import() {
    $this->importer->import();
    return $this->redirectResponse(Url::fromRoute('example_importer.settings'));
  }

  public function rollback() {
    $this->importer->rollback();
    return $this->redirectResponse(Url::fromRoute('example_importer.settings'));
  }

  public function reset() {
    $this->importer->reset();
    return $this->redirectResponse(Url::fromRoute('example_importer.settings'));
  }

  protected function redirectResponse($url) {
    return RedirectResponse::create($url->setAbsolute()->toString());
  }

}
