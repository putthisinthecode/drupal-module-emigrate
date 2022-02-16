<?php

namespace Drupal\emigrate\Commands;

use Consolidation\AnnotatedCommand\CommandData;
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

  /**
   * @var Emigrate
   */
  private $emigrate;

  /**
   * Initialize Emigrate objet before each command launch
   *
   * @hook pre-command *
   *
   * @param CommandData $commandData
   */
  public function preHook(CommandData $commandData) {
    parent::preHook($commandData);

    $this->emigrate = new Emigrate($this->getCurrentDirectory());
  }

  /**
   * @return mixed
   */
  public function getCurrentDirectory() {
    return $this->getConfig()->cwd();
  }

  /**
   * Export Drupal site.
   *
   * @command emigrate:export
   * @aliases em:ex
   */
  public function export() {
    $this->emigrate->export();
  }

  /**
   * Display migration status
   *
   * @command emigrate:status
   * $aliases em:st
   */
  public function status() {
    /**
     * @var \Drush\Style\DrushStyle $io
     */
    $io = $this->io();
    $io->title("Emigrate status");

    $headers = ['Option', 'Value'];
    $rows = [
      [
        'Emigrate directory',
        $this->emigrate->getDirectory(),
      ],
    ];


    $io->table($headers, $rows);
  }

  /**
   * Display migration status
   *
   * @command emigrate:describe-entity-bundle
   * $aliases em:deb
   */
  public function describeEntityBundle($entityTypeAndBundle) {

  }

}
