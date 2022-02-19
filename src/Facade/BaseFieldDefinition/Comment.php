<?php

namespace Drupal\emigrate\Facade\BaseFieldDefinition;

class Comment extends DefaultField {

  public function getId() {
    // TODO: Implement getId() method.
  }

  public function processFieldItem($fieldItem) {
    $comments = [];
    $node = $fieldItem->getEntity();
    $entity_manager = \Drupal::entityTypeManager();
    try {
      /** @var \Drupal\comment\CommentStorageInterface $storage */
      $storage = $entity_manager->getStorage('comment');
      /** @var \Drupal\comment\CommentFieldItemList $commentField */
      $commentField = $fieldItem;
      $commentsTree = $storage->loadThread($node, $commentField->getFieldDefinition()
        ->getName(), \Drupal\comment\CommentManagerInterface::COMMENT_MODE_FLAT);

      /** @var \Drupal\comment\Entity\Comment $comment */
      foreach ($commentsTree as $comment) {
        $commentFacade = $this->facadeFactory->createFromEntity($comment);
        $data = $commentFacade->getData();
        $data['id'] = $comment->id();
        $comments[] = $data;
      }

    } catch (\Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException $e) {
    } catch (\Drupal\Component\Plugin\Exception\PluginNotFoundException $e) {
    }


    $type = $this->getFieldDefinition()
      ->getItemDefinition()
      ->getSettings()['comment_type'];

    return [
      'type' => $type,
      'comments' => $comments,
    ];
  }

}