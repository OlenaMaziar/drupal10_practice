<?php

namespace Drupal\Tests\tamper\Functional\Plugin\Tamper;

/**
 * Tests the array select plugin.
 *
 * @coversDefaultClass \Drupal\tamper\Plugin\Tamper\ArraySelect
 * @group tamper
 */
class ArraySelectTest extends TamperPluginTestBase {

  /**
   * The ID of the plugin to test.
   *
   * @var string
   */
  protected static $pluginId = 'array_select';

  /**
   * {@inheritdoc}
   */
  public function formDataProvider(): array {
    return [
      'last' => [
        'expected' => [
          'select' => 'last',
        ],
        'edit' => [
          'select' => 'last',
        ],
      ],
      'all but last' => [
        'expected' => [
          'select' => 'all_but_last',
        ],
        'edit' => [
          'select' => 'all_but_last',
        ],
      ],
      'all but first' => [
        'expected' => [
          'select' => 'all_but_first',
        ],
        'edit' => [
          'select' => 'all_but_first',
        ],
      ],
      'integer' => [
        'expected' => [
          'select' => '3',
        ],
        'edit' => [
          'select' => 3,
        ],
      ],
      'invalid empty option' => [
        'expected' => [],
        'edit' => [],
        'errors' => [
          'Options are integer or last, all_but_last, all_but_first',
        ],
      ],
      'invalid option' => [
        'expected' => [],
        'edit' => [
          'select' => 4.5,
        ],
        'errors' => [
          'Options are integer or last, all_but_last, all_but_first',
        ],
      ],
    ];
  }

}
