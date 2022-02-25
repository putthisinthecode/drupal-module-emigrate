<?php

namespace Drupal\emigrate\Exporter;

interface ExporterInterface {

  public function getId();

  public function getData();

  public function getDebugData();


}
