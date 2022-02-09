<?php

namespace Drupal\emigrate\Writer;

class FilesTree
{
  private $facades = [];
  /**
   * @var array
   */
  private $options;
  private $index = [];

  public function __construct(array $options)
  {
    $this->options = $options;
  }

  public function add($facade)
  {
    $this->facades[] = $facade;
  }

  public function write()
  {
    $rootPath = $this->constructPath([$this->options['destination'], 'emigrate']);
    $entitiesDirectorySubPath = 'entities';
    $this->ensureDirectory($this->constructPath([$rootPath, $entitiesDirectorySubPath]));

    foreach ($this->facades as $facade) {
      $typeDirectorySubPath = $this->constructPath([$entitiesDirectorySubPath, $facade->getType()]);
      $this->ensureDirectory($this->constructPath([$rootPath, $typeDirectorySubPath]));
      $filename = $facade->getId() . '.json';
      $fileSubPath = $this->constructPath($typeDirectorySubPath, $filename);
      file_put_contents($this->constructPath([$rootPath, $fileSubPath]), json_encode($facade->getData()));
      $this->addToIndex($fileSubPath);
    }
    file_put_contents($this->constructPath([$rootPath, 'index.js']), json_encode($this->index));
  }

  /**
   * @param string $destination
   * @return void
   */
  public function ensureDirectory(string $destination, $subPath = NULL)
  {
    if (!is_dir($destination)) {
      mkdir($destination, 0777, true);
    }
  }

  /**
   * @param array $pathArray
   * @return string
   */
  public function constructPath($pathArray, $subPath = NULL): string
  {
    if (!is_array($pathArray)) {
      $pathArray = [$pathArray];
    }

    if (!empty($subPath)) {
      $pathArray[] = $subPath;
    }
    return join(DIRECTORY_SEPARATOR, $pathArray);
  }

  public function addToIndex($path)
  {
    $this->index[] = $path;
  }
}