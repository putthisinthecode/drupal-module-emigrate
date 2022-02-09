<?php

namespace Drupal\emigrate\Facade;

interface FacadeInterface
{
  public function getId();

  public function getData();

  public function getDebugData(): array;

  public function getType(): string;
}
