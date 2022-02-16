<?php
namespace Drupal\emigrate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\emigrate\Emigrate;
use Drupal\node\NodeInterface;
use Drupal\emigrate\Facade\FacadeFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewJson extends ControllerBase {
  public function json(NodeInterface $node) {
    $emigrate = new Emigrate(DRUPAL_ROOT . '/..');
    $exported = $emigrate->exportEntity($node);

    return new JsonResponse(
      $exported
    );
  }

}
