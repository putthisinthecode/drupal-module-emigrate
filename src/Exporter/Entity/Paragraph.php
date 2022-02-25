<?php

namespace Drupal\emigrate\Exporter\Entity;

class Paragraph extends DefaultEntity {

  public function getInlineData() {
    return $this->getData();
  }

  public function getData() {
    $data = [];

    $data = $this->processFields($data);

    return $data;
  }

}