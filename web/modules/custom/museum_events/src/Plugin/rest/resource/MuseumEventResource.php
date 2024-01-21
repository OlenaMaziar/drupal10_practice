<?php

declare(strict_types = 1);

namespace Drupal\museum_events\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Provides an Event Resource.
 *
 * @RestResource(
 *   id = "museum_events_resource",
 *   label = @Translation("City Museum events resource"),
 *   uri_paths = {
 *     "canonical" = "/event_resource_api/museum_events_resource"
 *   }
 * )
 */
class MuseumEventResource extends ResourceBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $nodes = \Drupal::entityTypeManager()->getStorage('node');
    $events = $nodes->loadByProperties([
      'type' => 'event',
    ]);

    if (empty($events)) {
      throw new NotFoundHttpException("Events not found");
    }

    foreach ($events as $event) {
      $response['events'][] = [
        'title' => $event->label(),
        'description' => $event->get('field_description')->value,
        'start_date' => $event->get('field_start_date')->value,
        'end_date' => $event->get('field_end_date')->value,
        //'image' => $project_status->getStatusImageUrl(),
        'canceled' => $event->get('field_canceled')->value,
        'available_tickets' => $event->get('field_number_of_tickets_availabl')->value,
        'price' => [
          'amount' => $event->get('field_price')->value,
          'currency' => $event->get('field_currency_code')->value
        ],
        'organizer' => [
          'id' => '',
          'name' => ''
        ]
      ];
    }

    return new ResourceResponse($response);
  }
}
