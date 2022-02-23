<?php

namespace Drupal\emigrate\Writer;

use Drupal\emigrate\Utils\FileManagement;

class FilesTree {

  use FileManagement;

  private $facades = [];

  /**
   * @var array
   */
  private $options;

  private $index = [];

  public function __construct(array $options) {
    $this->options = $options;
  }

  public function add($facade) {
    $this->facades[] = $facade;
  }

  public function write() {
    $rootPath = self::constructpath([
      $this->options['destination'],
      'emigrate',
    ]);
    $entitiesDirectorySubPath = 'entities';
    static::ensureDirectory(self::constructpath([
      $rootPath,
      $entitiesDirectorySubPath,
    ]));

    foreach ($this->facades as $facade) {
      $typeDirectorySubPath = self::constructpath([
        $entitiesDirectorySubPath,
        $facade->getEntityTypeId(),
        $facade->getBundle(),
      ]);
      static::ensureDirectory(self::constructpath([
        $rootPath,
        $typeDirectorySubPath,
      ]));
      $filename = $facade->getId() . '.json';
      $fileSubPath = self::constructpath($typeDirectorySubPath, $filename);
      file_put_contents(self::constructpath([
        $rootPath,
        $fileSubPath,
      ]), json_encode($facade->getData()));
      $this->addToIndex($fileSubPath);
    }
    file_put_contents(self::constructpath([
      $rootPath,
      'index.js',
    ]), json_encode($this->index));
  }

  public function addToIndex($path) {
    $this->index[] = $path;
  }

}