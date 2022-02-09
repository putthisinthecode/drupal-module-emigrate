<?php

namespace Drupal\emigrate\Facade;

use Drupal\emigrate\Facade\BaseFieldDefinition\Comment;
use Drupal\emigrate\Facade\BaseFieldDefinition\Image;
use Drupal\emigrate\Facade\BaseFieldDefinition\Link;
use Drupal\emigrate\Facade\BaseFieldDefinition\TextWithSummary;
use Drupal\emigrate\Facade\Entity\Node;
use \Drupal\emigrate\Facade\BaseFieldDefinition\DefaultField;
use Drupal\emigrate\Facade\BaseFieldDefinition\EntityReference;
use Drupal\emigrate\Facade\Entity\TaxonomyTerm;

class FacadeFactory
{
  const TYPE_METHOD_MAPPING = [
    'object' => 'getFacadeForObject'
  ];

  const OBJECT_CLASS_MAPPING = [
    'Drupal\node\Entity\Node' => Node::class,
    'Drupal\Core\Field\BaseFieldDefinition' => DefaultField::class,
    'Drupal\taxonomy\Entity\Term' => TaxonomyTerm::class,
    'Drupal\comment\Entity\Comment' => \Drupal\emigrate\Facade\Entity\Comment::class
  ];

  const FIELD_TYPE_CLASS_MAPPING = [
    'entity_reference' => EntityReference::class,
    'text_with_summary' => TextWithSummary::class,
    'comment' => Comment::class,
    'image' => Image::class,
    'link' => Link::class
  ];

  static function createFromEntity($element)
  {
    $facade = NULL;
    $type = gettype($element);
    $method = static::TYPE_METHOD_MAPPING[$type] ?? NULL;

    if (!empty($method)) {
      $facade = call_user_func('static::' . $method, $element);
    }

    return $facade;
  }

  static function createFromEntityFieldItem($entity, $key)
  {
    $field = $entity->$key;
    $type = $field->getFieldDefinition()->getType();
    $className = static::FIELD_TYPE_CLASS_MAPPING[$type] ?? DefaultField::class;
    $facade = new $className($field);

    return $facade;
  }

  static protected function getFacadeForObject($object)
  {
    $className = static::OBJECT_CLASS_MAPPING[get_class($object)] ?? NULL;

    if (!empty($className)) {
      $facade = new $className($object);
    }

    return $facade;
  }
}
