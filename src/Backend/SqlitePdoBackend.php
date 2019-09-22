<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Backend;

use SetBased\Stratum\Backend;
use SetBased\Stratum\Config;
use SetBased\Stratum\RoutineLoaderWorker;
use SetBased\Stratum\RoutineWrapperGeneratorWorker;
use SetBased\Stratum\StratumStyle;

/**
 * The PhpStratum's backend for SQLite using PDO.
 */
class SqlitePdoBackend extends Backend
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function createRoutineLoaderWorker(Config $settings, StratumStyle $io): ?RoutineLoaderWorker
  {
    return new SqlitePdoRoutineLoaderWorker($settings, $io);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function createRoutineWrapperGeneratorWorker(Config $settings,
                                                      StratumStyle $io): ?RoutineWrapperGeneratorWorker
  {
    return new SqlitePdoRoutineWrapperGeneratorPdoWorker($settings, $io);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
