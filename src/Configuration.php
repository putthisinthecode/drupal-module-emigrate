<?php

namespace Drupal\emigrate;

use Yosymfony\Toml\Toml;

class Configuration {

  private static $defaultConfiguration;

  private $configurationArray;


  public function __construct($configurationArray) {
    $this->configurationArray = $configurationArray;
  }

  public static function loadFromFile($path) {
    if (empty(static::$defaultConfiguration)) {
      static::$defaultConfiguration = new static(Toml::ParseFile($path));
    }

    return static::$defaultConfiguration;
  }

  public static function getDefaultConfiguration() {
    return static::$defaultConfiguration;
  }

  public function getExporterForClass($className) {
    return $this->configurationArray[$className];
  }

  public function getExporterClassNameForEntityType($entityType) {
    $exporterClassName = NULL;
    if (!empty($this->configurationArray['entity'][$entityType]['exporter'])) {
      $exporterClassName = $this->configurationArray['entity'][$entityType]['exporter'];
    }

    return $exporterClassName;
  }

  public function getConfigurationForEntityBundle($entityType, $bundle) {
    $bundleConfiguration = [];

    if (!empty($this->configurationArray['entity'][$entityType][$bundle])) {
      $bundleConfiguration = $this->configurationArray['entity'][$entityType][$bundle];
    }

    $entityConfiguration = $this->configurationArray['entity'][$entityType];

    return array_merge_recursive($entityConfiguration, $bundleConfiguration);
  }

  public function getExporterForFieldItem($type) {
    $exporter = NULL;

    if (!empty($this->configurationArray['entity']['field_config'][$type])) {
      $exporter = $this->configurationArray['entity']['field_config'][$type];
    }

    return $exporter;
  }

}