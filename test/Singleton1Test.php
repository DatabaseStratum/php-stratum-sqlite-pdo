<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\Middle\Exception\ResultException;

/**
 * Test cases for stored routines with designation type singleton1.
 */
class Singleton1Test extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton1 must return 1 value and 1 value only.
   */
  public function test01(): void
  {
    $ret = $this->dataLayer->tstTestSingleton1a(1);
    self::assertEquals(1, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type singleton1 returns 0 rows.
   */
  public function test02(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestSingleton1a(0);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type singleton1 returns more than 1 rows.
   */
  public function test03(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestSingleton1a(2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  An exception must be thrown when a stored routine with designation type singleton1 return type bool returns 0 rows.
   */
  public function test11(): void
  {
    $this->expectException(ResultException::class);
    $value = $this->dataLayer->tstTestSingleton1b(0, 1);
    self::assertFalse($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton1 and return type bool must return false when selecting 1 row
   * with null value.
   */
  public function test12(): void
  {
    $value = $this->dataLayer->tstTestSingleton1b(1, null);
    self::assertFalse($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton1 and return type bool must return false when selecting 1 row
   * with empty value.
   */
  public function test13(): void
  {
    $value = $this->dataLayer->tstTestSingleton1b(1, 0);
    self::assertFalse($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton1 and return type bool must return true when selecting 1 row
   * with non-empty value.
   */
  public function test14(): void
  {
    $value = $this->dataLayer->tstTestSingleton1b(1, 123);
    self::assertTrue($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type singleton1 and return type bool returns
   * more than 1 row.
   */
  public function test15(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestSingleton1b(2, 1);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

