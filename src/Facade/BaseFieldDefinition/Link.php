<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class Link extends DefaultField
{

  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function prepareDataAtIndex(int $index)
  {
    $data = NULL;

    if (!$this->element->isEmpty()) {
      $link = $this->element->first();
      $uri = $link->getUrl();
      $data = [
        'uri' => $uri->getUri(),
        'title' => $link->title,
      ];
    }
    return $data;
  }
}