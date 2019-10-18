<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for stored routines with designation type lastInsertId.
 */
class LastInsertIdTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type lastInsertId must returns IDs start with 1.
   */
  public function test01()
  {
    $id = $this->dataLayer->tstTestLastIncrementId('Hello');
    self::assertEquals(1, $id);

    $id = $this->dataLayer->tstTestLastIncrementId('World');
    self::assertEquals(2, $id);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

