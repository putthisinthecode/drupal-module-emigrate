<?php

namespace Drupal\emigrate\Exporter\Entity;

use Drupal\emigrate\Configuration;
use Drupal\emigrate\Exporter\ExporterBase;
use Drupal\field\Entity\FieldConfig;

class DefaultEntity extends ExporterBase {

  public function getData() {
    return $this->getData();
  }

  public function getId() {
    return $this->element->id();
  }

  public function getInlineData() {
    return [
      'title' => $this->element->getTitle(),
    ];
  }

  public function getBundle() {
    return $this->element->bundle();
  }

  /**
   * @param $data
   *
   * @return mixed
   */
  public function processFields($data) {
    $fields = $this->getFieldsToExport();

    foreach ($fields as $fieldName) {
      $exporter = $this->exporterFactory->createFromField($this->element, $fieldName);
      $data = $exporter->enrichData($data);
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
    $fields = $entityFieldManager->getFieldDefinitions($this->element->getEntityTypeId(), $this->element->bundle());

    return array_keys(array_filter($fields, [
      $this,
      'fieldShouldBeExported',
    ]));
  }

  public function mustExportEntity() {
    return TRUE;
  }

  protected function getDataFromFieldItemList($field) {
    $data = [];
    $fieldItemList = $this->element->get($field->getName());

    foreach ($fieldItemList as $index => $fieldItem) {

      /**
       * @var \Drupal\emigrate\Exporter\BaseFieldDefinition\DefaultField
       */
      $exporter = $this->exporterFactory->createFromFieldName($this->element, );
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
    if (!empty($bundleConfiguration['exclude_fields'])) {
      $excludedField = $bundleConfiguration['exclude_fields'];
    }
    else {
      $excludedField = [];
    }
    return !in_array($field->getName(), $excludedField) && $field instanceof FieldConfig;
  }

  public function getEntityTypeId() {
    return $this->element->getEntityTypeId();
  }
}