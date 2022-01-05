<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

use Drupal\emigrate\Facade\FacadeBase;
use Drupal\emigrate\Facade\FacadeFactory;

class EntityReference extends DefaultField
{
  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function prepareDataAtIndex(int $index)
  {
    $data = NULL;
    $referencedEntities = $this->element->referencedEntities();
    if (count($referencedEntities) > 0) {
      $referencedEntity = $referencedEntities[$index];
      $referencedEntityFacade = FacadeFactory::createFromEntity($referencedEntity);
      $data = $referencedEntityFacade->getInlineData();
    }

    return $data;
  }

  public function getTargetType()
  {
    return $this->getSettings()['target_type'];
  }
}