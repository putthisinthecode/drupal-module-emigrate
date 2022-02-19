<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class Image extends DefaultField
{
  public function getData()
  {
    /**
     * @TODO : Should return NULL when empty and cardinality is 1
     */
    $data = [];

    $files = $this->fieldItemList->referencedEntities();

    if (count($files) > 0) {
      foreach($files as $file) {
        $data[] = [
          'id' => $file->id(),
          'uri' => $this->removeFilePathPrefix($file->getFileUri()),
          'name' => $file->getFilename(),
        ];

      }
    }

    $data = $this->enforceCardinality($data);

    return $data;
  }

  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function getDebugData(): array
  {
    $data = [];
    foreach ($this->element->referencedEntities() as $referencedEntity) {
      $data[] = [
        $referencedEntity
      ];
    }

    return $data;

  }

  private function removeFilePAthPrefix($filePath) {
    /**
     * @TODO : support public/private files
     */
    $prefix = 'public://';

    if (substr($filePath, 0, strlen($prefix)) == $prefix) {
      $filePath = substr($filePath, strlen($prefix));
    }

    return $filePath;
  }
}