<?php

namespace Drupal\emigrate\Exporter\BaseFieldDefinition;

class EntityReference extends DefaultField {

  public function getId() {
    // TODO: Implement getId() method.
  }


  public function getData() {
    $data = [];
    $referencedEntities = $this->fieldItemList->referencedEntities();
    if (count($referencedEntities) > 0) {
      foreach ($referencedEntities as $referencedEntity) {
        $referencedEntityExporter = $this->exporterFactory->createFromEntity($referencedEntity);
        $data[] = $referencedEntityExporter->getInlineData();

      }

    }

    return $this->enforceCardinality($data);
  }

}