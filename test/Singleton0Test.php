<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\Middle\Exception\ResultException;

/**
 * Test cases for stored routines with designation type singleton0.
 */
class Singleton0Test extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton0 must return null.
   */
  public function test01(): void
  {
    $value = $this->dataLayer->tstTestSingleton0a(0);
    self::assertNull($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton0 must return 1 value.
   */
  public function test02(): void
  {
    $value = $this->dataLayer->tstTestSingleton0a(1);
    self::assertIsInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type singleton0 returns more than 1 rows.
   */
  public function test03(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestSingleton0a(2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton0 and return type bool must return false when selecting 0 rows.
   */
  public function test11(): void
  {
    $value = $this->dataLayer->tstTestSingleton0b(0, 1);
    self::assertFalse($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton0 and return type bool|null must return false when selecting 1 row
   * with null value.
   */
  public function test12(): void
  {
    $value = $this->dataLayer->tstTestSingleton0b(1, null);
    self::assertFalse($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton0 and return type bool must return false when selecting 1 row
   * with empty value.
   */
  public function test13(): void
  {
    $value = $this->dataLayer->tstTestSingleton0b(1, 0);
    self::assertFalse($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type singleton0 and return type bool must return true when selecting 1 row
   * with non-empty value.
   */
  public function test14(): void
  {
    $value = $this->dataLayer->tstTestSingleton0b(1, 123);
    self::assertTrue($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type singleton0  and return type bool returns
   * more than 1 row.
   */
  public function test15(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestSingleton0b(2, 1);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

