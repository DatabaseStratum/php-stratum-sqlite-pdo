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
   * Tests for quoteReal.
   */
  public function testQuoteFloat1(): void
  {
    $value    = 123.123;
    $expected = '123.123';
    self::assertSame($expected, $this->dataLayer->quoteReal($value), var_export($value, true));

    $value    = 123.123;
    $expected = '123.123';
    self::assertSame($expected, $this->dataLayer->quoteReal($value), var_export($value, true));

    $value    = 0.0;
    $expected = '0';
    self::assertSame($expected, $this->dataLayer->quoteReal($value), var_export($value, true));

    $value    = null;
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteReal($value), var_export($value, true));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for quoteInt.
   */
  public function testQuoteInt1(): void
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
   * Tests for quoteVarchar.
   */
  public function testQuoteString1(): void
  {
    $value    = '123';
    $expected = "'123'";
    self::assertSame($expected, $this->dataLayer->quoteVarchar($value), var_export($value, true));

    $value    = '0';
    $expected = "'0'";
    self::assertSame($expected, $this->dataLayer->quoteVarchar($value), var_export($value, true));

    $value    = '';
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteVarchar($value), var_export($value, true));

    $value    = null;
    $expected = 'null';
    self::assertSame($expected, $this->dataLayer->quoteVarchar($value), var_export($value, true));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test strtr does not mix up parameters with nearly same name.
   */
  public function testStrtr(): void
  {
    $row = $this->dataLayer->tstStrtr(1, 100, 10);
    self::assertSame(1, $row['p1']);
    self::assertSame(100, $row['p100a']);
    self::assertSame(10, $row['p10']);
    self::assertSame(100, $row['p100b']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

