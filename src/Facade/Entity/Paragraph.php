<?php

namespace Drupal\emigrate\Facade\Entity;

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