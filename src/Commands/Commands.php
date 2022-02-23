<?php

namespace Drupal\emigrate\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use Drupal\emigrate\Configuration;
use Drupal\emigrate\Emigrate;
use Drupal\emigrate\Ui\Drush;
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
   * @hook validate *
   *
   * @param CommandData $commandData
   */
  public function validate(CommandData $commandData) {
    $this->emigrate = new Emigrate($this->getCurrentDirectory(), new Drush($this->io()));
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
    $this->checkConfiguration();
    $this->emigrate->export();
  }

  /**
   * @return void
   * @throws \Exception
   */
  public function checkConfiguration() {
    if (!$this->emigrate->isConfigured()) {
      throw(new \Exception('You must provide a configuration file. Simple way to do this is running drush emigrate:init'));
    }
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
    $this->checkConfiguration();

    $io = $this->io();
    $io->title("Emigrate status");
    $configuration = $this->emigrate->getConfiguration();
    $headers = ['Option', 'Value'];
    $rows = [
      [
        'Configuration file',
        $configuration->getConfigurationFilePath(),
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

  /**
   * Display migration status
   *
   * @command emigrate:init
   * $aliases em:init
   */
  public function init() {
    Configuration::create($this->getCurrentDirectory());
  }

}
