<?php

namespace Drupal\emigrate\Facade\Entity;

class DefaultEntity extends \Drupal\emigrate\Facade\FacadeBase
{
  public function getData() {
    return $this->prepareDataAtIndex(0);
  }

  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function prepareDataAtIndex(int $index)
  {
    // TODO: Implement getData() method.
  }

  public function getInlineData()
  {
    return [
      'title' => $this->element->getTitle()
    ];
  }
}