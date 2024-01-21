<?php

namespace Drupal\tamper\Plugin\Tamper;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tamper\Exception\TamperException;
use Drupal\tamper\TamperableItemInterface;
use Drupal\tamper\TamperBase;

/**
 * Plugin implementation for selecting item(s) from array.
 *
 * @Tamper(
 *   id = "array_select",
 *   label = @Translation("Array select"),
 *   description = @Translation("Select items from an array. Options include N, last, all but last, all but first."),
 *   category = "List",
 *   handle_multiples = TRUE
 * )
 */
class ArraySelect extends TamperBase {

  const SETTING_SELECT = 'select';
  const NON_INT_OPTIONS = ['last', 'all_but_last', 'all_but_first'];

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config[self::SETTING_SELECT] = '';
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form[self::SETTING_SELECT] = [
      '#type' => 'textfield',
      '#title' => $this->t('Item to select. Options include N (integer), last, all_but_last, all_but_first.'),
      '#default_value' => $this->getSetting(self::SETTING_SELECT),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    $select = $form_state->getValue(self::SETTING_SELECT);
    if (!((ctype_digit($select)) || in_array($select, self::NON_INT_OPTIONS))) {
      $form_state->setErrorByName(self::SETTING_SELECT, $this->t('Options are integer or %non_int_options', [
        '%non_int_options' => implode(', ', self::NON_INT_OPTIONS),
      ]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->setConfiguration([
      self::SETTING_SELECT => $form_state->getValue(self::SETTING_SELECT),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function tamper($data, TamperableItemInterface $item = NULL) {
    if (!is_array($data)) {
      throw new TamperException('Input should be an array.');
    }

    $select = $this->getSetting(self::SETTING_SELECT);

    if (isset($data[$select])) {
      return $data[$select];
    }

    switch ($select) {
      case 'last':
        return ($data[count($data) - 1]);

      case 'all_but_last':
        array_pop($data);
        return $data;

      case 'all_but_first':
        array_shift($data);
        return $data;

    }
  }

  /**
   * {@inheritdoc}
   */
  public function multiple() {
    return TRUE;
  }

}
