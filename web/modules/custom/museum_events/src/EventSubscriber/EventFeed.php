<?php

namespace Drupal\museum_events\EventSubscriber;

use Drupal\Core\File\FileSystemInterface;
use Drupal\feeds\Event\ParseEvent;
use Drupal\feeds\EventSubscriber\AfterParseBase;
use Drupal\feeds\Feeds\Item\ItemInterface;
use Drupal\media\Entity\Media;

/**
* Reacts on events being processed.
*/
class EventFeed extends AfterParseBase {

  /**
   * {@inheritdoc}
   */
  public function applies(ParseEvent $event) {
    return $event->getFeed()->getType()->id() === 'museum_events_feed';
  }

  /**
   * {@inheritdoc}
   */
  protected function alterItem(ItemInterface $item, ParseEvent $event) {
    $url = $item->get('image');
    $headers = get_headers($url, 1);
    $content_type = $headers["Content-Type"];
    $type = preg_split("/[;]+/", $content_type);

    $mime_types = [
      'text/plain' => 'txt',
      'text/html' => 'htm',
      'text/html' => 'html',
      'text/html' => 'php',
      'text/css' => 'css',
      'application/javascript' => 'js',

      // images
      'image/png' => 'png',
      'image/jpeg' => 'jpeg',
      'image/gif' => 'gif',
      'image/bmp' =>'bmp',
      'image/vnd.microsoft.icon' =>'ico',
      'image/tiff' =>'tiff',
      'image/tiff' =>'tif',
      'image/svg+xml' =>'svg',
      'image/svg+xml' =>'svgz',

      // adobe
      'application/pdf' => 'pdf',
    ];

    if (array_key_exists($type[0], $mime_types)) {
      $extension = $mime_types[$type[0]];
      $filename = str_replace(".","_",microtime(true)). '.' . $extension;
    }

    if ($filename) {
      file_put_contents($filename, file_get_contents($url));
      $image_data = file_get_contents($filename);
      $file_repository = \Drupal::service('file.repository');
      $image = $file_repository->writeData($image_data, "public://images/".$filename, FileSystemInterface::EXISTS_REPLACE);

      $image_media = Media::create([
        'name' => $image->get('filename')->value,
        'bundle' => 'image',
        'uid' => $image->get('uid')->value,
        'langcode' => $image->get('langcode')->value,
        'status' => $image->get('status')->value,
        'field_media_image' => [
          'target_id' => $image->id(),
          'alt' => t('My media alt attribute'),
          'title' => t('My media title attribute'),
        ],
      ]);
      $image_media->save();
      $item->set('image', $image->get('filename')->value);
    }
    else
    {
      $item->set('image', $url);
    }
  }

}
