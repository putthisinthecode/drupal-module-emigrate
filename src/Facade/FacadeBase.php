<?php

namespace Drupal\emigrate\Facade;

abstract class FacadeBase implements FacadeInterface {

  protected $element;

  /**
   * @var FacadeFactory
   */
  protected $facadeFactory;

  public function __construct($element) {
    $this->element = $element;

    $this->facadeFactory  = FacadeFactory::getDefaultFactory();
  }

  public function getDebugData() {
    return [];
  }

}
