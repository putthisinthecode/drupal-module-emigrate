<?php

namespace Drupal\emigrate\Exporter;

abstract class ExporterBase implements ExporterInterface {

  protected $element;

  /**
   * @var ExporterFactory
   */
  protected $exporterFactory;

  public function __construct($element) {
    $this->element = $element;

    $this->exporterFactory  = ExporterFactory::getDefaultFactory();
  }

  public function getDebugData() {
    return [];
  }

}
