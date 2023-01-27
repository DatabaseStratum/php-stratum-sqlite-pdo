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
  public function test1(): void
  {
    $ret = $this->dataLayer->tstTestRow1a(1);
    self::assertIsArray($ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type row1 returns 0 rows.
   */
  public function test2(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestRow1a(0);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception must be thrown when a stored routine with designation type row1 returns more than 1 rows.
   */
  public function test3(): void
  {
    $this->expectException(ResultException::class);
    $this->dataLayer->tstTestRow1a(2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test conversion to native type (PDO converts all columns to strings).
   */
  public function testConversion(): void
  {
    $row = $this->dataLayer->tstTestRow1Conversion();
    self::assertIsArray($row);

    self::assertIsInt($row['c_int']);
    self::assertIsFloat($row['c_numeric']);
    self::assertIsFloat($row['c_float']);
    self::assertIsFloat($row['c_real']);
    self::assertIsFloat($row['c_double']);
    self::assertIsString($row['c_varchar']);
    self::assertIsString($row['c_text']);
    self::assertIsString($row['c_blob']);

    self::assertSame(1, $row['c_int']);
    self::assertSame(1.1, $row['c_numeric']);
    self::assertSame(2.2, $row['c_float']);
    self::assertSame(3.3, $row['c_real']);
    self::assertSame(4.4, $row['c_double']);
    self::assertSame('varchar', $row['c_varchar']);
    self::assertSame('text', $row['c_text']);
    self::assertSame('blob', $row['c_blob']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple queries on multiple lines.
   */
  public function testMultiQueriesMultipleLines(): void
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
  public function testSingleQueryWithSemiColon(): void
  {
    $queries = 'select 1 as one;';

    $row = $this->dataLayer->executeRow1($queries);
    self::assertSame(1, $row['one']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple queries on multiple lines.
   */
  public function testSingleQueryWithoutSemiColon(): void
  {
    $queries = 'select 1 as one';

    $row = $this->dataLayer->executeRow1($queries);
    self::assertSame(1, $row['one']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

