<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for quoting variables.
 */
class QuoteTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for invalid argument values.
   *
   * @param string $column The column/parameter name.
   * @param mixed  $value  The value for the column/parameter.
   */
  public function genericInvalid($column, $value)
  {
    try
    {
      $this->dataLayer->tstTest01(($column=='int') ? $value : null,
                                  ($column=='real') ? $value : null,
                                  ($column=='text') ? $value : null,
                                  ($column=='blob') ? $value : null);
      self::assertTrue(false, "column: $column, value: $value");
    }
    catch (\TypeError $e)
    {
      self::assertTrue(true);
    }
    catch (\RuntimeException $e)
    {
      self::assertTrue(true);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for valid argument values.
   *
   * @param string $column The column/parameter name.
   * @param mixed  $value  The value for the column/parameter.
   */
  public function genericValid(string $column, $value)
  {
    $this->dataLayer->tstTest01(($column=='int') ? $value : null,
                                ($column=='real') ? $value : null,
                                ($column=='text') ? $value : null,
                                ($column=='blob') ? $value : null);
    self::assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test illegal values will raise an exception.
   */
  public function testInvalid()
  {
    $tests = [];

    $tests[] = ['column' => 'int', 'value' => 'abc'];
    $tests[] = ['column' => 'real', 'value' => 'abc'];
    $tests[] = ['column' => 'double', 'value' => 'abc'];
    foreach ($tests as $test)
    {
      $this->genericInvalid($test['column'], $test['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test all column types are quoted properly.
   */
  public function testValid()
  {
    $tests = [];

    $tests[] = ['column' => 'int', 'value' => 1];
    $tests[] = ['column' => 'real', 'value' => 0.1];
    $tests[] = ['column' => 'blob', 'value' => '1010'];
    $tests[] = ['column' => 'text', 'value' => '1234'];
    $tests[] = ['column' => 'text', 'value' => 'abc'];
    $tests[] = ['column' => 'text', 'value' => "0xC8 ' --"];
    $tests[] = ['column' => 'blob', 'value' => "\xFF\x7F\x80\x5c\x00\x10"];

    foreach ($tests as $test)
    {
      $this->genericValid($test['column'], $test['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------

}
//----------------------------------------------------------------------------------------------------------------------
