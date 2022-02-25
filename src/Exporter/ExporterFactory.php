<?php

namespace Drupal\emigrate\Exporter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\emigrate\Configuration;
use Drupal\emigrate\Exporter\BaseFieldDefinition\Comment;
use Drupal\emigrate\Exporter\BaseFieldDefinition\DefaultField;
use Drupal\emigrate\Exporter\BaseFieldDefinition\EntityReference;
use Drupal\emigrate\Exporter\BaseFieldDefinition\Image;
use Drupal\emigrate\Exporter\BaseFieldDefinition\Link;
use Drupal\emigrate\Exporter\BaseFieldDefinition\TextWithSummary;

class ExporterFactory {

  const FIELD_TYPE_CLASS_MAPPING = [
    'entity_reference' => EntityReference::class,
    'text_with_summary' => TextWithSummary::class,
    'comment' => Comment::class,
    'image' => Image::class,
    'link' => Link::class,
  ];

  /**
   * @var self
   */
  private static $defaultFactory;

  private $configuration;

  public function __construct($configuration) {
    $this->configuration = $configuration;
  }

  public static function init() {
    if (empty(static::$defaultFactory)) {
      $configuration = Configuration::getDefaultConfiguration();
      static::$defaultFactory = new static($configuration);
    }

    return static::$defaultFactory;
  }

  /**
   * @return ExporterFactory
   */
  public static function getDefaultFactory() {
    return static::$defaultFactory;
  }

  public function createFromField($entity, $fieldName) {
    $fieldDefinition = $entity->getFieldDefinition($fieldName);
    if (!empty($this->configuration->getExporterForFieldItem($fieldDefinition
      ->getType()))) {
      $className = $this->configuration->getExporterForFieldItem($fieldDefinition
        ->getType())['exporter'];
    }
    else {
      $className = DefaultField::class;
    }

    return new $className($entity, $fieldName);
  }

  function createFromEntity(EntityInterface $entity) {
    $exporter = NULL;

    $entityType = $entity->getEntityTypeId();

    $exporterClassName = $this->configuration->getExporterClassNameForEntityType($entityType);

    if (!empty($exporterClassName)) {
      $exporter = new $exporterClassName($entity);
    }

    return $exporter;
  }


}
