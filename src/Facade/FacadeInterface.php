<?php
namespace Drupal\emigrate\Facade;

interface FacadeInterface {
    public function getId();
    public function prepareDataAtIndex(int $index);
  public function getDebugData() : array;
}
