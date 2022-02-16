<?php

namespace Drupal\emigrate\Facade;

use Drupal\Core\Entity\EntityInterface;
use Drupal\emigrate\Configuration;
use Drupal\emigrate\Facade\BaseFieldDefinition\Comment;
use Drupal\emigrate\Facade\BaseFieldDefinition\DefaultField;
use Drupal\emigrate\Facade\BaseFieldDefinition\EntityReference;
use Drupal\emigrate\Facade\BaseFieldDefinition\Image;
use Drupal\emigrate\Facade\BaseFieldDefinition\Link;
use Drupal\emigrate\Facade\BaseFieldDefinition\TextWithSummary;

class FacadeFactory {

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
   * @return FacadeFactory
   */
  public static function getDefaultFactory() {
    return static::$defaultFactory;
  }

  static function createFromEntityFieldItem($entity, $key) {
    $field = $entity->$key;
    $type = $field->getFieldDefinition()->getType();
    if (!empty(static::FIELD_TYPE_CLASS_MAPPING[$type])) {
      $className = static::FIELD_TYPE_CLASS_MAPPING[$type];
    }
    else {
      $className = DefaultField::class;
    }

    return new $className($field);
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
