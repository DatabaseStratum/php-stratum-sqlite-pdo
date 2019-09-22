<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\SqlitePdo\Exception\SqlitePdoDataLayerException;

/**
 * Test cases for multi query statements.
 */
class NoneMultiTest extends DataLayerTestCase
{
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
drop table if exists TST_FOOBAR;
EOT;

    $count = $this->dataLayer->executeNoneMulti($queries);
    self::assertSame(3, $count);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple queries on multiple lines.
   */
  public function testSingleQuery()
  {
    $queries = 'drop table if exists TST_FOOBAR;';

    $count = $this->dataLayer->executeNoneMulti($queries);
    self::assertSame(1, $count);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with syntax error.
   */
  public function testSyntaxErrorLineNumber()
  {
    try
    {
      $queries = <<< EOT
drop table if exists TST_FOOBAR;

drop table if exists TST_FOOBAR;

not-a-query;
EOT;

      $this->dataLayer->executeNoneMulti($queries);
    }
    catch (\Throwable $exception)
    {
      self::assertInstanceOf(SqlitePdoDataLayerException::class, $exception);
      self::assertStringContainsString('line 5', $exception->getMessage());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

