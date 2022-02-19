<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class EntityReference extends DefaultField {

  public function getId() {
    // TODO: Implement getId() method.
  }


  public function getData() {
    $data = [];
    $referencedEntities = $this->fieldItemList->referencedEntities();
    if (count($referencedEntities) > 0) {
      foreach ($referencedEntities as $referencedEntity) {
        $referencedEntityFacade = $this->facadeFactory->createFromEntity($referencedEntity);
        $data[] = $referencedEntityFacade->getInlineData();

      }

    }

    return $this->enforceCardinality($data);
  }

}