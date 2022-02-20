<?php

namespace Drupal\emigrate\Facade\Entity;

interface EntityInterface {

  public function getBundle();

  public function getEntityTypeId();

}