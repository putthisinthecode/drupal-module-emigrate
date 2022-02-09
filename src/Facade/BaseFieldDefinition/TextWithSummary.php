<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class TextWithSummary extends DefaultField
{
  public function prepareDataAtIndex(int $index)
  {
    $content = $this->element->get(0);

    return [
      'type' => $this->element->getFieldDefinition()->getType(),
      'format' => $this->element->format,
      'text' => $content->value,
      'summary' => $content->summary,
    ];
  }

}