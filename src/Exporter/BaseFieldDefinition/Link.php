<?php

namespace Drupal\emigrate\Exporter\BaseFieldDefinition;

class Link extends DefaultField
{

  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function processFieldItem($fieldItem)
  {
    $data = NULL;

    if (!$this->isEmpty()) {
      if ($fieldItem->isExternal()) {
        $uri = $fieldItem->getUrl();
        $data = [
          'uri' => $uri->getUri(),
          'title' => $fieldItem->title,
        ];
      }
    }
    return $data;
  }
}