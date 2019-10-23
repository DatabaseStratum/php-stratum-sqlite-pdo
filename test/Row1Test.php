<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\Middle\Exception\ResultException;

/**
 * Test cases for stored routines with designation type row1.
 */
class Row1Test extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stored routine with designation type row1 must return 1 row and 1 row only.
   */
  public function test1()
  {
    $ret = $this->dataLayer->tstTestRow1a(1);
    self::assertIsArray($ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type row1 returns 0 rows.
   */
  public function test2()
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestRow1a(0);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type row1 returns more than 1 rows.
   */
  public function test3()
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestRow1a(2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple queries on multiple lines.
   */
  public function testMultiQueriesMultipleLines()
  {
    $queries = <<< EOT
drop table if exists TST_FOOBAR;
-- This is a comment with a ; but not a the end.
delete from TST_FOO1 where tst_text = ';';
select 4 as 'four';
EOT;

    $row = $this->dataLayer->executeRow1($queries);
    self::assertSame(4, $row['four']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple queries on multiple lines.
   */
  public function testSingleQueryWithSemiColon()
  {
    $queries = 'select 1 as one;';

    $row = $this->dataLayer->executeRow1($queries);
    self::assertSame(1, $row['one']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple queries on multiple lines.
   */
  public function testSingleQueryWithoutSemiColon()
  {
    $queries = 'select 1 as one';

    $row = $this->dataLayer->executeRow1($queries);
    self::assertSame(1, $row['one']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

