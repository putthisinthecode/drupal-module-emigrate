<?php

namespace Drupal\emigrate\Utils;

trait FileManagement {

  /**
   * @param array $pathArray
   *
   * @return string
   */
  public static function constructPath($pathArray, $subPath = NULL) {
    if (!is_array($pathArray)) {
      $pathArray = [$pathArray];
    }

    if (!empty($subPath)) {
      $pathArray[] = $subPath;
    }
    return join(DIRECTORY_SEPARATOR, $pathArray);
  }

  /**
   * @param string $destination
   *
   * @return void
   */
  public static function ensureDirectory(string $destination, $subPath = NULL) {
    if (!is_dir($destination)) {
      mkdir($destination, 0777, TRUE);
    }
  }

}