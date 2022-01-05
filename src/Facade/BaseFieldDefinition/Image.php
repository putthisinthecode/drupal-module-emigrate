<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class Image extends DefaultField
{
  public function prepareDataAtIndex(int $index)
  {
    $data = [];

    $referencedEntities = $this->element->referencedEntities();

    if (count($referencedEntities) > 0) {

      $file = $referencedEntities[$index];

      $data = [
        'id' => $file->id(),
        'uri' => $file->getFileUri(),
        'name' => $file->getFilename(),
      ];

    }

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

}