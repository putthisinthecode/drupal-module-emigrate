<?php

namespace Drupal\emigrate;

use Drupal\emigrate\Utils\FileManagement;
use Yosymfony\Toml\Toml;

/**
 * TODO: Extract configuration loading in another class
 */
class Configuration {

  use FileManagement;

  const TOML_FILE_NAME = 'emigrate.toml';

  const EMIGRATE_DIRECTORY = 'emigrate';

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

  public static function createDefaultConfiguration($directory) {
    self::ensureConfigurationFile();
  }

  public static function ensureConfigurationFile($directory) {
    if (!file_exists(self::constructPath($directory, self::TOML_FILE_NAME))) {
      $module_handler = \Drupal::service('module_handler');
      $module_path = $module_handler->getModule('emigrate')->getPath();
      $filePath = static::constructPath([
        $module_path,
        'recipes',
      ], self::TOML_FILE_NAME);
      $directoryPath = static::constructPath([
        $directory,
        static::EMIGRATE_DIRECTORY,
      ]);

      $configurationfilePath = static::constructPath($directoryPath, static::TOML_FILE_NAME);

      copy($filePath, $configurationfilePath);

      $drupalConfig = \Drupal::service('config.factory')
        ->getEditable('emigrate.configuration');

      $drupalConfig->set('directory_path', $directoryPath)
        ->set('configuration_file_name', static::TOML_FILE_NAME)
        ->save();
    }
  }

  public static function create($directory) {
    static::ensureDirectory(self::constructPath([
      $directory,
      self::EMIGRATE_DIRECTORY,
    ]));
    self::ensureConfigurationFile($directory);
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

  public function getRootEntities() {
    if (!empty($this->configurationArray['global']['root_entities']) && is_array($this->configurationArray['global']['root_entities'])) {
      return $this->configurationArray['global']['root_entities'];
    }
    else {
      return [];
    }
  }

  public function getConfigurationFilePath() {
    $drupalConfig = \Drupal::service('config.factory')
      ->get('emigrate.configuration');

    return self::constructPath(
      $drupalConfig->get('directory_path'),
      $drupalConfig->get('configuration_file_name')
    );
  }

}