<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\Middle\BulkHandler;

/**
 * Bulk handler for testing purposes.
 */
class TestBulkHandler implements BulkHandler
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function row(array $row): void
  {
    var_export($row);
    echo PHP_EOL;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function start(): void
  {
    echo 'Start', PHP_EOL;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function stop(): void
  {
    echo 'Stop', PHP_EOL;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
