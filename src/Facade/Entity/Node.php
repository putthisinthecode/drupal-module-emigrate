<?php

namespace Drupal\emigrate\Facade\Entity;

use Drupal\emigrate\Configuration;

class Node extends DefaultEntity {

  public function prepareDataAtIndex(int $index) {
    $data = [
      'id' => $this->getId(),
      'language' => $this->getLanguage(),
      'type' => $this->element->bundle(),
      'published' => $this->element->isPublished(),
      'featured' => $this->element->isPromoted(),
      'sticky' => $this->element->isSticky(),
      'slug' => $this->getSlug(),
    ];

    $fields = $this->getFieldsToExport();

    foreach ($fields as $fieldName) {
      $exporter = $this->facadeFactory->createFromField($this->element, $fieldName);
      $data = $exporter->enrichData($data);
    }

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

  /**
   * @param $fields
   *
   * @return array
   */
  public function getFieldsToExport() {
    $entityFieldManager = \Drupal::service('entity_field.manager');
    $fields = $entityFieldManager->getFieldDefinitions('node', $this->element->bundle());

    return array_keys(array_filter($fields, [
      $this,
      'fieldShouldBeExported',
    ], ARRAY_FILTER_USE_KEY));
  }

  protected function getDataFromFieldItemList($field) {
    $data = [];
    $exporter = $this->facadeFactory->createFromField($field);
    $fieldItemList = $this->element->get($field->getName());
    $exporter = $this->facadeFactory->createFromField($fieldItemList);

    foreach ($fieldItemList as $index => $fieldItem) {

      /**
       * @var \Drupal\emigrate\Facade\BaseFieldDefinition\DefaultField
       */
      $exporter = $this->facadeFactory->createFromFieldName($this->element, );
      $data[$index] = $exporter->getData();
    }

    if ($field->getCardinality() == 1) {
      $data = current($data);
    }

    return $data;
  }

  protected function fieldShouldBeExported($field) {
    $configuration = Configuration::getDefaultConfiguration();
    $bundleConfiguration = $configuration->getConfigurationForEntityBundle($this->element->getEntityTypeID(), $this->element->bundle());
    $excludedField = $bundleConfiguration['exclude_fields'];
    return !in_array($field, $excludedField);
  }

}
