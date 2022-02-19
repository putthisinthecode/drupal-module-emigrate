<?php

namespace Drupal\emigrate\Facade\Entity;

use Drupal\emigrate\Facade\FacadeBase;

class DefaultEntity extends FacadeBase {

  public function getData() {
    return $this->prepareDataAtIndex(0);
  }

  public function getId() {
    // TODO: Implement getId() method.
  }

  public function getInlineData() {
    return [
      'title' => $this->element->getTitle(),
    ];
  }

  public function getType(): string {
    return $this->element->bundle();
  }

}