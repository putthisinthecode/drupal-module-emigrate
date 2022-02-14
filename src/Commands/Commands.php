<?php

namespace Drupal\emigrate\Commands;

use Drupal\emigrate\Configuration;
use Drupal\emigrate\Facade\FacadeFactory;
use Drupal\emigrate\Writer\FilesTree;
use Drupal\node\Entity\Node;
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
    $this->loadConfiguration();
    FacadeFactory::init($this->configuration);
    $facadeFactory = FacadeFactory::getDefaultFactory();
    $nids = \Drupal::entityQuery('node')->execute();
    $writer = new FilesTree([
      'destination' => $this->getCurrentDirectory(),
    ]);
    foreach (Node::loadMultiple($nids) as $node) {
      $exporter = $facadeFactory->createFromEntity($node);
      $writer->add($exporter);
    }
    $writer->write();
  }

  /**
   * @return void
   */
  public function loadConfiguration() {
    $this->configuration = Configuration::loadFromFile($this->getCurrentDirectory() . "/emigrate.toml");
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
