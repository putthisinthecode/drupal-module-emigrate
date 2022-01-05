<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class TextWithSummary extends DefaultField
{
  public function prepareDataAtIndex(int $index)
  {
    return [
      'type' => $this->element->getFieldDefinition()->getType(),
      'format' => $this->element->format,
      'text' => $this->element->first()->value,
    ];
  }

}