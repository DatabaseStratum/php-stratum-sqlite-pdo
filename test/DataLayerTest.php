<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for class SqlitePdoDataLayer.
 */
class DataLayerTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for quoteFloat.
   */
  public function testQuoteFloat1()
  {
    $value    = 123.123;
    $expected = '123.123';
    self::assertSame($expected, $this->dataLayer->quoteFloat($value), var_export($value, true));

    $value    = 123.123;
    $expected = '123.123';
    self::assertSame($expected, $this->dataLayer->quoteFloat($value), var_export($value, true));

    $value    = 0.0;
    $expected = '0';
    self::assertSame($expected, $this->dataLayer->quoteFloat($value), var_export($value, true));

    $value    = null;
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteFloat($value), var_export($value, true));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for quoteInt.
   */
  public function testQuoteInt1()
  {
    $value    = 123;
    $expected = '123';
    self::assertSame($expected, $this->dataLayer->quoteInt($value), var_export($value, true));

    $value    = 0;
    $expected = '0';
    self::assertSame($expected, $this->dataLayer->quoteInt($value), var_export($value, true));

    $value    = null;
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteInt($value), var_export($value, true));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for quoteString.
   */
  public function testQuoteString1()
  {
    $value    = '123';
    $expected = "'123'";
    self::assertSame($expected, $this->dataLayer->quoteString($value), var_export($value, true));

    $value    = '0';
    $expected = "'0'";
    self::assertSame($expected, $this->dataLayer->quoteString($value), var_export($value, true));

    $value    = '';
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteString($value), var_export($value, true));

    $value    = null;
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteString($value), var_export($value, true));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

