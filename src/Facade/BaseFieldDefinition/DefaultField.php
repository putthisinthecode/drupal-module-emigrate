<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

use Drupal\emigrate\Facade\FacadeBase;
use Drupal\emigrate\Facade\FacadeFactory;

class DefaultField extends FacadeBase
{
  public function getData()
  {
    if ($this->getCardinality() == 1) {
      $data = $this->prepareDataAtIndex(0);
    } else {
      $data = [];
      foreach ($this->element as $index => $entry) {
        $data[$index] = $this->prepareDataAtIndex($index);
      }
    }
    return $data;
  }

  public function prepareDataAtIndex(int $index)
  {
    return $this->element->value;
  }

  public function getId()
  {
    return $this->element->getName();
  }

  public function setData($data)
  {
    $this->element->setValue($data);
  }

  public function getType(): string
  {
    return $this->element->getFieldDefinition()->getType();
  }

  public function getDebugData(): array
  {
    return [
      'type' => $this->getType()
    ];
  }

  public function getStorageDefinition()
  {
    return $this->getFieldDefinition()->getFieldStorageDefinition();
  }

  public function getSettings()
  {
    return $this->getFieldDefinition()->getSettings();
  }

  public function getFieldDefinition()
  {
    return $this->element->getFieldDefinition();
  }

  /**
   * @return mixed
   */
  public function getCardinality()
  {
    return $this->getStorageDefinition()->getCardinality();
  }

  /**
   * @return mixed
   */
  public function isEmpty()
  {
    return $this->element->isEmpty();
  }

}
 