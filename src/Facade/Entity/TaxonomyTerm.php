<?php

namespace Drupal\emigrate\Facade\Entity;

class TaxonomyTerm extends DefaultEntity
{

  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function prepareDataAtIndex(int $index)
  {
    // TODO: Implement getData() method.
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