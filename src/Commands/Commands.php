<?php

namespace Drupal\emigrate\Commands;

use Drupal\emigrate\Facade\FacadeFactory;
use Drupal\node\Entity\Node;
use Drush\Commands\DrushCommands;
use Drupal\emigrate\Writer\FilesTree;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 */
class Commands extends DrushCommands
{
  /**
   * Echos back hello with the argument provided.
   * @command emigrate:export
   * @aliases eme
   */
  public function export()
  {
    $nids = \Drupal::entityQuery('node')->execute();
    $writer = new FilesTree([
//      'destination' => $this->getConfig()->cwd()
      'destination' => '/opt/shared'
    ]);
    foreach (Node::loadMultiple($nids) as $node) {
      $exporter = FacadeFactory::createFromEntity($node);
      $writer->add($exporter);
    }
    $writer->write();
  }
}
