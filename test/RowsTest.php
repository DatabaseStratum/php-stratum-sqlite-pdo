<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for stored routines with designation type rows.
 */
class RowsTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type rows must return an empty array when no rows are selected.
   */
  public function testRows0(): void
  {
    $ret = $this->dataLayer->tstTestRows1(0);
    self::assertIsArray($ret);
    self::assertCount(0, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type rows must return an array with 1 row when only 1 row is selected.
   */
  public function testRows1(): void
  {
    $ret = $this->dataLayer->tstTestRows1(1);
    self::assertIsArray($ret);
    self::assertCount(1, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type rows must return an array with 3 rows when 3 rows are selected.
   */
  public function testRows3(): void
  {
    $ret = $this->dataLayer->tstTestRows1(3);
    self::assertIsArray($ret);
    self::assertCount(3, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

