<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

use Drupal\emigrate\Facade\FacadeBase;

class Comment extends DefaultField
{
  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function prepareDataAtIndex(int $index)
  {
    $type = $this->element->getFieldDefinition()->getItemDefinition()->getSettings()['comment_type'];

    return [
      'type' => $type
    ];
  }

}