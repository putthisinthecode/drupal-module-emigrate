<?php

namespace Drupal\emigrate\Facade\Entity;

use Drupal\emigrate\Configuration;

class Node extends DefaultEntity {

  public function getData() {
    $data = [
      'id' => $this->getId(),
      'language' => $this->getLanguage(),
      'type' => $this->element->bundle(),
      'published' => $this->element->isPublished(),
      'featured' => $this->element->isPromoted(),
      'sticky' => $this->element->isSticky(),
      'slug' => $this->getSlug(),
    ];

    $data = $this->processFields($data);

    return $data;
  }

  public function getId() {
    return (int) $this->element->id();
  }

  public function getLanguage() {
    $language = $this->element->language();

    return $language->getId();
  }

  public function getSlug() {
    return \Drupal::service('path_alias.manager')
      ->getAliasByPath('/node/' . $this->element->id());
  }

}
