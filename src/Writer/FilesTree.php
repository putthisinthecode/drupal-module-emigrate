<?php

namespace Drupal\emigrate\Writer;

use Drupal\emigrate\Utils\FileManagement;

class FilesTree {

  use FileManagement;

  private $exporters = [];

  /**
   * @var array
   */
  private $options;

  private $index = [];

  public function __construct(array $options) {
    $this->options = $options;
  }

  public function add($exporter) {
    $this->exporters[] = $exporter;
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

    foreach ($this->exporters as $exporter) {
      $typeDirectorySubPath = self::constructpath([
        $entitiesDirectorySubPath,
        $exporter->getEntityTypeId(),
        $exporter->getBundle(),
      ]);
      static::ensureDirectory(self::constructpath([
        $rootPath,
        $typeDirectorySubPath,
      ]));
      $filename = $exporter->getId() . '.json';
      $fileSubPath = self::constructpath($typeDirectorySubPath, $filename);
      file_put_contents(self::constructpath([
        $rootPath,
        $fileSubPath,
      ]), json_encode($exporter->getData()));
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