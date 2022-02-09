<?php

namespace Drupal\emigrate\Facade\Entity;

class Comment extends DefaultEntity
{
  public function prepareDataAtIndex(int $index): array
  {
    $comment = $this->element;

    $parent = $comment->hasParentComment()?$comment->getParentComment()->get('cid')->first()->value:null;

    return [
      'subject' => $comment->getSubject(),
      'parent' => $parent,
      'text' => $comment->get('comment_body')->value
    ];
  }
}
