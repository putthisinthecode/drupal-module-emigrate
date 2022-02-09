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

    if (!$this->isEmpty()) {
      $link = $this->element->get(0);
      if ($link->isExternal()) {
        $uri = $link->getUrl();
        $data = [
          'uri' => $uri->getUri(),
          'title' => $link->title,
        ];
      }
    }
    return $data;
  }
}