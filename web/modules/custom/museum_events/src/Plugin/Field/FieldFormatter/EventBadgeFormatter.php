<?php

namespace Drupal\museum_events\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'Event badge' formatter.
 *
 * @FieldFormatter(
 *   id = "field_number_of_tickets_availabl",
 *   label = @Translation("Badge"),
 *   field_types = {
 *     "integer"
 *   }
 * )
 */
class EventBadgeFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the random string.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      if($item->value == 0) {
        $badge = t('Sold Out');
        $element[$delta] = ['#markup' => $badge];
      }
      elseif ($item->value <= 10) {
        $element[$delta] = ['#markup' => $item->value . ' ' .t('seats left')];
      }
    }

    return $element;
  }

}
