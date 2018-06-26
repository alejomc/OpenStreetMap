<?php

namespace Drupal\osm\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityManager;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OsmController.
 */
class OsmController extends ControllerBase {


  /**
   * OsmController constructor.
   * @param \Drupal\Core\Entity\EntityManager $entityManager
   */
  public function __construct(EntityManager $entityManager) {

    $this->entityManager = $entityManager;
  }

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The Drupal service container.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')
    );
  }


  /**
   * Printosm.
   *
   * @return string
   *   Return Hello string.
   */
  public function printOSM() {
    return [
      '#theme' => 'osm',
      '#attached' => [
        'library' => [
          'osm/map_custon',
        ],
        'drupalSettings' => [
          'leaflet' => ['markers' => $this->getMarkers()],
        ]
      ]
    ];
  }

  /**
   * Get Markers info.
   */
  protected function getMarkers() {
    $nodeMarkers = $this->entityManager->getStorage('node')->loadMultiple($this->getContentByQuery('osm_marker'));
    $markers = [];
    /** @var Node $marker */
    foreach ($nodeMarkers as $marker) {
      $markers[] = [
        'id' => $marker->id(),
        'title' => $marker->label(),
        'description' => $marker->get('body')->value,
        'lat' => $marker->get('field_latitude')->getString(),
        'lng' => $marker->get('field_longitude')->getString()
      ];
    }

    return $markers;
  }



  /**
   * {@inheritdoc}
   */
  protected function getContentByQuery($type, $status = 1) {
    return $this->entityManager->getStorage('node')->getQuery()
      ->sort('nid', 'DESC')
      ->condition('status', 1)
      ->condition('type', $type)
      ->execute();
  }


}
