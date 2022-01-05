<?php

namespace Drupal\emigrate\Facade\Entity;

use Drupal\emigrate\Facade\BaseFieldDefinition\DefaultField;
use Drupal\emigrate\Facade\FacadeBase;
use Drupal\emigrate\Facade\FacadeFactory;

class Node extends DefaultEntity
{
  const EXCLUDED_FIELD = ['nid', 'uuid', 'vid', 'revision_uid', 'revision_log', 'revision_timestamp', 'langcode', 'type', 'status', 'uid', 'promote', 'sticky', 'default_langcode',
    'revision_default', 'revision_translation_affected', 'path'];

  static protected function fieldShouldBeExported($field): bool
  {
    return !in_array($field, self::EXCLUDED_FIELD);
  }

  public function prepareDataAtIndex(int $index): array
  {
    $fields = $this->getFieldsToExport();

    $data = [
      'id' => $this->getId(),
      'language' => $this->getLanguage(),
      'type' => $this->element->bundle(),
      'published' => $this->element->isPublished(),
      'featured' => $this->element->isPromoted(),
      'sticky' => $this->element->isSticky(),
      'slug' => $this->getSlug(),
    ];

    foreach ($fields as $fieldKey => $field) {
      $fieldFacade = FacadeFactory::createFromEntityFieldItem($this->element, $fieldKey);
      $fieldData = $fieldFacade->getData();
      $data[$fieldKey] = $fieldData;

    }

    return $data;
  }

  /**
   * @param $fields
   * @return array
   */
  public function getFieldsToExport(): array
  {
    $entityFieldManager = \Drupal::service('entity_field.manager');
    $fields = $entityFieldManager->getFieldDefinitions('node', $this->element->bundle());

    return array_filter($fields, [$this, 'fieldShouldBeExported'], ARRAY_FILTER_USE_KEY);
  }

  public function getId()
  {
    return $this->element->id();
  }

  public function getLanguage()
  {
    $language = $this->element->language();

    return $language->getId();
  }

  public function getSlug() {
    $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/'.$this->element->id());

    return $alias;
  }

}
