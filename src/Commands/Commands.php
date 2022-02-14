<?php

namespace Drupal\emigrate\Commands;

use Drupal\emigrate\Emigrate;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 */
class Commands extends DrushCommands {

  private $configuration;

  /**
   * Export Drupal site.
   *
   * @command emigrate:export
   * @aliases em:ex
   */
  public function export() {
    $emigrate = new Emigrate($this->getCurrentDirectory());

    $emigrate->export();
  }


  /**
   * @return mixed
   */
  public function getCurrentDirectory() {
    return $this->getConfig()->cwd();
  }

  /**
   * Display migration status
   *
   * @command emigrate:status
   * $aliases em:st
   */
  public function status() {
    $this->loadConfiguration();
    print_r($this->configuration);
  }

}
