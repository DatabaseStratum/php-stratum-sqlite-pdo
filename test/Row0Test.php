<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\Exception\ResultException;

/**
 * Test cases for stored routines with designation type row0.
 */
class Row0Test extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type row0 must return null.
   */
  public function test1()
  {
    $ret = $this->dataLayer->tstTestRow0a(0);
    self::assertNull($ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type row0 must return 1 row.
   */
  public function test2()
  {
    $ret = $this->dataLayer->tstTestRow0a(1);
    self::assertIsArray($ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type row0 returns more than 1 rows.
   */
  public function test3()
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestRow0a(2);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

