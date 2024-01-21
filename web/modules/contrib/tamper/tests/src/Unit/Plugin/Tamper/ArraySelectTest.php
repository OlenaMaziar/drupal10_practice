<?php

namespace Drupal\Tests\tamper\Unit\Plugin\Tamper;

use Drupal\tamper\Exception\TamperException;
use Drupal\tamper\Plugin\Tamper\ArraySelect;

/**
 * Tests the array select plugin.
 *
 * @coversDefaultClass \Drupal\tamper\Plugin\Tamper\ArraySelect
 * @group tamper
 */
class ArraySelectTest extends TamperPluginTestBase {

  /**
   * The mock data to test.
   *
   * @var array
   */
  protected $originalData;

  /**
   * {@inheritdoc}
   */
  protected function instantiatePlugin() {
    return new ArraySelect([], 'array_select', [], $this->getMockSourceDefinition());
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->originalData = ['foo', 'bar', 'fizz', 'buzz'];
    parent::setUp();
  }

  /**
   * Test the plugin with a single value.
   */
  public function testSingleValue() {
    $this->expectException(TamperException::class);
    $this->expectExceptionMessage('Input should be an array.');
    $config = [
      ArraySelect::SETTING_SELECT => '0',
    ];
    $plugin = new ArraySelect($config, 'array_select', [], $this->getMockSourceDefinition());
    $plugin->tamper('Not an array.');
  }

  /**
   * Test the plugin for last value.
   */
  public function testLastValue() {
    $config = [
      ArraySelect::SETTING_SELECT => 'last',
    ];
    $plugin = new ArraySelect($config, 'array_select', [], $this->getMockSourceDefinition());
    $expected = 'buzz';
    $this->assertEquals($expected, $plugin->tamper($this->originalData));
  }

  /**
   * Test the plugin for all but last value.
   */
  public function testAllButLastValue() {
    $config = [
      ArraySelect::SETTING_SELECT => 'all_but_last',
    ];
    $plugin = new ArraySelect($config, 'array_select', [], $this->getMockSourceDefinition());
    $expected = ['foo', 'bar', 'fizz'];
    $this->assertEquals($expected, $plugin->tamper($this->originalData));
  }

  /**
   * Test the plugin for all but first value.
   */
  public function testAllButFirstValue() {
    $config = [
      ArraySelect::SETTING_SELECT => 'all_but_first',
    ];
    $plugin = new ArraySelect($config, 'array_select', [], $this->getMockSourceDefinition());
    $expected = ['bar', 'fizz', 'buzz'];
    $this->assertEquals($expected, $plugin->tamper($this->originalData));
  }

  /**
   * Test the plugin for index value in array.
   */
  public function testIndexInArray() {
    $config = [
      ArraySelect::SETTING_SELECT => 2,
    ];
    $plugin = new ArraySelect($config, 'array_select', [], $this->getMockSourceDefinition());
    $expected = 'fizz';
    $this->assertEquals($expected, $plugin->tamper($this->originalData));
  }

  /**
   * Test the plugin for index not in array.
   */
  public function testIndexNotInArray() {
    $config = [
      ArraySelect::SETTING_SELECT => 10,
    ];
    $plugin = new ArraySelect($config, 'array_select', [], $this->getMockSourceDefinition());
    $this->assertEmpty($plugin->tamper($this->originalData));
  }

}
