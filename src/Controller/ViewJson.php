<?php
namespace Drupal\emigrate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\emigrate\Facade\FacadeFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewJson extends ControllerBase {
  public function json(NodeInterface $node) {
    $facadeFactory = FacadeFactory::getDefaultFactory();
    $nodeFacade = $facadeFactory->createFromEntity($node);

    $response = new JsonResponse(
      $nodeFacade->getData()
    );
    return $response;
  }

}
