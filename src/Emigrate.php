<?php

namespace Drupal\emigrate;

use Drupal\emigrate\Facade\FacadeFactory;
use Drupal\emigrate\Writer\FilesTree;

class Emigrate {

  private $directory;

  /**
   * @var null
   */
  private $configuration;

  private $ui;

  /**
   * @param string $directory
   * @param \Drupal\emigrate\Ui\UiInterface $ui
   */
  public function __construct($directory, $ui) {
    $this->directory = $directory;
    $this->ui = $ui;
    $this->loadConfiguration();
    FacadeFactory::init();
  }

  /**
   * @return void
   */
  public function loadConfiguration() {
    $configurationPath = $this->getDirectory() . "/emigrate/emigrate.toml";
    if (file_exists($configurationPath)) {
      $configuration = Configuration::loadFromFile($configurationPath);
      $this->setConfiguration($configuration);
    }
  }

  /**
   * @return mixed
   */
  public function getDirectory() {
    return $this->directory;
  }

  /**
   * @param mixed $directory
   */
  public function setDirectory($directory) {
    $this->directory = $directory;
  }

  /**
   * @return null
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * @param \Drupal\emigrate\Configuration $configuration
   */
  public function setConfiguration($configuration) {
    $this->configuration = $configuration;
  }

  public function export() {
    $this->configuration->getRootEntities();
    $facadeFactory = FacadeFactory::getDefaultFactory();
    $rootEntities = $this->configuration->getRootEntities();
    $entityTypeManager = \Drupal::entityTypeManager();
    $writer = new FilesTree([
      'destination' => $this->getDirectory(),
    ]);

    foreach ($rootEntities as $entityType) {
      $storage = $entityTypeManager->getStorage($entityType);
      $ids = \Drupal::entityQuery($entityType)->execute();
      foreach ($storage->loadMultiple($ids) as $node) {
        $exporter = $facadeFactory->createFromEntity($node);
        if ($exporter->mustExportEntity()) {
          $writer->add($exporter);
        }
      }
    }

    $writer->write();
  }

  public function exportEntity($entity) {
    $factory = FacadeFactory::getDefaultFactory();
    $exporter = $factory->createFromEntity($entity);
    return $exporter->getData();
  }

  public function isConfigured() {
    return !empty($this->configuration);
  }
}