<?php

namespace Drupal\emigrate\Exporter\Entity;

interface EntityInterface {

  public function getBundle();

  public function getEntityTypeId();

}