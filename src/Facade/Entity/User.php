<?php

namespace Drupal\emigrate\Facade\Entity;

class User extends DefaultEntity {

  public function getData() {
    /**
     * @var \Drupal\user\UserInterface $user
     */
    $user = $this->element;
    $data = [
      'id' => $this->element->id(),
      'name' => $user->getAccountName(),
      'email' => $user->getEmail(),
      'timezone' => $user->getTimeZone(),
      'created' => $user->getCreatedTime(),
    ];

    return $data;
  }

  public function mustExportEntity() {
    return $this->getId() != 0;
  }

}