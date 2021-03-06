<?php

namespace Drupal\emigrate\Exporter\BaseFieldDefinition;

use Drupal\emigrate\Exporter\ExporterFactory;

class DefaultField {

  /**
   * @var \Drupal\Core\Field\FieldItemListInterface
   */
  protected $fieldItemList;

  /**
   * @var \Drupal\emigrate\Exporter\ExporterFactory
   */
  protected $exporterFactory;

  /**
   * @var \Drupal\Core\Entity\FieldableEntityInterface
   */
  private $entity;

  /**
   * @var string $fieldName
   */
  private $fieldName;

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface|null
   */
  private $fieldDefinition;

  /**
   * @var \Drupal\Core\Field\FieldConfigInterface
   */
  private $fieldConfig;

  /**
   * @var \Drupal\Core\Field\FieldStorageDefinitionInterface
   */
  private $fieldStorage;

  protected $configuration;

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   * @param string $fieldName
   */
  public function __construct($entity, $fieldName, $configuration) {
    $this->entity = $entity;
    $this->fieldName = $fieldName;
    $this->fieldDefinition = $entity->getFieldDefinition($fieldName);
    $this->fieldConfig = $this->fieldDefinition->getConfig($fieldName);
    $this->fieldStorage = $this->fieldConfig->getFieldStorageDefinition();
    $this->fieldItemList = $entity->get($fieldName);
    $this->exporterFactory = ExporterFactory::getDefaultFactory();
    $this->configuration = $configuration;
  }

  public function getId() {
    return $this->element->getName();
  }

  public function setData($data) {
    $this->element->setValue($data);
  }

  public function getDebugData() {
    return [
      'type' => $this->getType(),
    ];
  }

  public function getType() {
    return $this->element->getFieldDefinition()->getType();
  }

  public function getSettings() {
    return $this->getFieldDefinition()->getSettings();
  }

  /**
   * @return \Drupal\Core\Field\FieldDefinitionInterface|null
   */
  public function getFieldDefinition() {
    return $this->fieldDefinition;
  }

  /**
   * @return mixed
   */
  public function isEmpty() {
    return $this->fieldItemList->isEmpty();
  }

  public function enrichData($data) {
    $data[$this->fieldDefinition->getName()] = $this->getData();

    return $data;
  }

  public function getData() {
    $data = [];

    foreach ($this->fieldItemList as $index => $fieldItem) {
      $data[] = $this->processFieldItem($fieldItem);
    }

    $data = $this->enforceCardinality($data);

    return $data;
  }

  /**
   * @param $fieldItem
   *
   * @return mixed
   */
  public function processFieldItem($fieldItem) {
    return $fieldItem->value;
  }

  /**
   * @param array $data
   *
   * @return array|false|mixed
   */
  public function enforceCardinality($data) {
    if ($this->getCardinality() == 1) {
      if (!empty($data)) {
        $data = reset($data);
      }
      else {
        $data = NULL;
      }
    }
    return $data;
  }

  /**
   * @return mixed
   */
  public function getCardinality() {
    return $this->getStorageDefinition()->getCardinality();
  }

  public function getStorageDefinition() {
    return $this->getFieldDefinition()->getFieldStorageDefinition();
  }

}