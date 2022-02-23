<?php

namespace Drupal\emigrate\Ui;

class Drush implements \Drupal\emigrate\Ui\UiInterface {

  public function __construct($io) {
    $this->io = $io;
  }

  public function error($error) {
    $this->io->error($error);
  }

}