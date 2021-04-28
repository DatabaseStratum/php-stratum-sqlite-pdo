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
array(5) {
  ["tst_c00"]=>
  int(1)
  ["tst_c01"]=>
  string(1) "a"
  ["tst_c02"]=>
  string(1) "b"
  ["tst_c03"]=>
  string(2) "c1"
  ["tst_c04"]=>
  string(1) "d"
}
array(5) {
  ["tst_c00"]=>
  int(2)
  ["tst_c01"]=>
  string(1) "a"
  ["tst_c02"]=>
  string(1) "b"
  ["tst_c03"]=>
  string(2) "c2"
  ["tst_c04"]=>
  string(1) "d"
}
array(5) {
  ["tst_c00"]=>
  int(3)
  ["tst_c01"]=>
  string(1) "a"
  ["tst_c02"]=>
  string(1) "b"
  ["tst_c03"]=>
  string(2) "c3"
  ["tst_c04"]=>
  string(1) "d"
}
Stop
EOL;
    self::assertSame(trim($expected), trim($output));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

