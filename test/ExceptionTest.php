<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\SqlitePdo\Exception\SqlitePdoQueryErrorException;

/**
 * Test cases for multi query statements.
 */
class ExceptionTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with syntax error.
   */
  public function testSyntaxErrorLineNumberWithQuery()
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
      self::assertInstanceOf(SqlitePdoQueryErrorException::class, $exception);
      self::assertStringContainsString('line 5', $exception->getMessage());
      self::assertStringContainsString('syntax error', $exception->getError());
      self::assertContains('<error>5 not-a-query</error>', $exception->styledQuery());
      self::assertSame('HY000', $exception->getErrorCode());
      self::assertSame('SQLite Error', $exception->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with syntax error.
   */
  public function testSyntaxErrorLineNumberWithSingleLineQuery()
  {
    try
    {
      $this->dataLayer->executeNone('not-a-query');
    }
    catch (\Throwable $exception)
    {
      self::assertInstanceOf(SqlitePdoQueryErrorException::class, $exception);
      self::assertStringContainsString('line 1', $exception->getMessage());
      self::assertStringContainsString('syntax error', $exception->getError());
      self::assertContains('<error>1 not-a-query</error>', $exception->styledQuery());
      self::assertSame('HY000', $exception->getErrorCode());
      self::assertSame('SQLite Error', $exception->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with syntax error.
   */
  public function testSyntaxErrorLineNumberWithMethod()
  {
    try
    {
      $this->dataLayer->tstTestIllegalQuery();
    }
    catch (\Throwable $exception)
    {
      self::assertInstanceOf(SqlitePdoQueryErrorException::class, $exception);
      self::assertStringContainsString('line 10', $exception->getMessage());
      self::assertContains('<error>10 select * from NOT_EXISTS</error>', $exception->styledQuery());
      self::assertSame('HY000', $exception->getErrorCode());
      self::assertSame('SQLite Error', $exception->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

