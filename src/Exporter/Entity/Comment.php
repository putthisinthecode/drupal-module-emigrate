<?php

namespace Drupal\emigrate\Exporter\Entity;

class Comment extends DefaultEntity
{
  public function getData()
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
