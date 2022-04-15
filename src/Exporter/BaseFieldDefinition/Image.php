<?php

namespace Drupal\emigrate\Exporter\BaseFieldDefinition;

class Image extends DefaultField {

  public function getData() {
    $data = [];

    $files = $this->fieldItemList->referencedEntities();

    if (count($files) > 0) {
      foreach ($files as $file) {
        $data[] = $this->processEntity($file);
      }
    }

    return $this->enforceCardinality($data);
  }

  public function processEntity($entity) {

    return [
      'id' => $this->extractId($entity),
      'uri' => $this->extractUri($entity),
      'name' => $this->extractFilename($entity),
    ];

  }

  /**
   * @param $entity
   *
   * @return mixed
   */
  public function extractId($entity) {
    return $entity->id();
  }

  /**
   * @param $entity
   *
   * @return false|mixed|string
   */
  public function extractUri($entity) {
    return  $this->getPrefix().$this->removeFilePathPrefix($entity->getFileUri());
  }

  private function removeFilePathPrefix($filePath) {
    /**
     * @TODO : support public/private files
     */
    $prefix = 'public://';

    if (substr($filePath, 0, strlen($prefix)) == $prefix) {
      $filePath = substr($filePath, strlen($prefix));
    }

    return $filePath;
  }

  /**
   * @param $entity
   *
   * @return mixed
   */
  public function extractFilename($entity) {
    return $entity->getFilename();
  }

  public function getId() {
    // TODO: Implement getId() method.
  }

  public function getDebugData() {
    $data = [];
    foreach ($this->element->referencedEntities() as $referencedEntity) {
      $data[] = [
        $referencedEntity,
      ];
    }

    return $data;

  }

  public function getPrefix() {
    $prefix = '';
    if (!empty($this->configuration['prefix'])) {
      $prefix =  $this->configuration['prefix'];
    }

    return $prefix;
  }
}
