<?php

namespace Drupal\emigrate\Facade;

abstract class FacadeBase implements FacadeInterface
{
  protected $element;

  public function __construct($element)
  {
    $this->element = $element;
  }

  public function getDebugData(): array
  {
    return [];
  }
}
