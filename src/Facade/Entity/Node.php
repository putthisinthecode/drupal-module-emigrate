<?php

namespace Drupal\emigrate\Facade\Entity;

use Drupal\emigrate\Configuration;
use Drupal\emigrate\Facade\FacadeFactory;

class Node extends DefaultEntity {

  public function prepareDataAtIndex(int $index) {
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
   *
   * @return array
   */
  public function getFieldsToExport() {
    $entityFieldManager = \Drupal::service('entity_field.manager');
    $fields = $entityFieldManager->getFieldDefinitions('node', $this->element->bundle());

    return array_filter($fields, [
      $this,
      'fieldShouldBeExported',
    ], ARRAY_FILTER_USE_KEY);
  }

  public function getId() {
    return (int) $this->element->id();
  }

  public function getLanguage() {
    $language = $this->element->language();

    return $language->getId();
  }

  public function getSlug() {
    $alias = \Drupal::service('path_alias.manager')
      ->getAliasByPath('/node/' . $this->element->id());

    return $alias;
  }

  protected function fieldShouldBeExported($field) {
    $configuration = Configuration::getDefaultConfiguration();
    $bundleConfiguration = $configuration->getConfigurationForEntityBundle($this->element->getEntityTypeID(), $this->element->bundle());
    $excludedField = $bundleConfiguration['exclude_fields'];
    return !in_array($field, $excludedField);
  }

}
