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

      $this->dataLayer->executeNone($queries);
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

