<?php

namespace Drupal\emigrate\Facade\Entity;

class TaxonomyTerm extends DefaultEntity
{

  public function getData()
  {
    return [
      'id' => $this->element->id(),
      'title' => $this->element->getName(),
      'description' => $this->element->getDescription(),
      'vocabulary' => $this->element->bundle(),
    ];
  }

  public function getInlineData() {
    return [
      'id' => $this->element->id(),
      'title' => $this->element->getName(),
      'description' => $this->element->getDescription(),
      'vocabulary' => $this->element->bundle(),
    ];
  }
}