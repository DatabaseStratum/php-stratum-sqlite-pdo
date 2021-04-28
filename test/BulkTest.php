<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for stored routines with designation type bulk.
 */
class BulkTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type rows must return an empty array when no rows are selected.
   */
  public function testBulk()
  {
    $handler  = new TestBulkHandler();

    $this->dataLayer->tstTestBulk($handler, 3);
    $output = $this->getActualOutputForAssertion();
    $expected = <<< EOL
Start
array (
  'tst_c00' => 1,
  'tst_c01' => 'a',
  'tst_c02' => 'b',
  'tst_c03' => 'c1',
  'tst_c04' => 'd',
)
array (
  'tst_c00' => 2,
  'tst_c01' => 'a',
  'tst_c02' => 'b',
  'tst_c03' => 'c2',
  'tst_c04' => 'd',
)
array (
  'tst_c00' => 3,
  'tst_c01' => 'a',
  'tst_c02' => 'b',
  'tst_c03' => 'c3',
  'tst_c04' => 'd',
)
Stop
EOL;
    self::assertSame(trim($expected), trim($output));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

