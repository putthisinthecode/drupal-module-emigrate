<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

use Drupal\emigrate\Facade\FacadeBase;
use Drupal\emigrate\Facade\FacadeFactory;

class Comment extends DefaultField
{
  public function getId()
  {
    // TODO: Implement getId() method.
  }

  public function prepareDataAtIndex(int $index)
  {
    $comments = 0;
    $node = $this->element->getEntity();
    $entity_manager = \Drupal::entityTypeManager();
    try {
      /** @var \Drupal\comment\CommentStorageInterface $storage */
      $storage = $entity_manager->getStorage('comment');
      /** @var \Drupal\comment\CommentFieldItemList $commentField */
      $commentField = $this->element;
      $comments = $storage->loadThread($node, $commentField->getFieldDefinition()->getName(), \Drupal\comment\CommentManagerInterface::COMMENT_MODE_FLAT);

      /** @var \Drupal\comment\Entity\Comment $comment */
      foreach ($comments as $comment) {
        $commentFacade = FacadeFactory::createFromEntity($comment);
        $comments[] = $commentFacade->getData();
      }

    } catch (\Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException $e) {
    } catch (\Drupal\Component\Plugin\Exception\PluginNotFoundException $e) {
    }


    $type = $this->element->getFieldDefinition()->getItemDefinition()->getSettings()['comment_type'];

    return [
      'type' => $type,
      'comments' => $comments
    ];
  }

}