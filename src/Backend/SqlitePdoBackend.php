<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Backend;

use SetBased\Stratum\Backend\Backend;
use SetBased\Stratum\Backend\Config;
use SetBased\Stratum\Backend\RoutineLoaderWorker;
use SetBased\Stratum\Backend\RoutineWrapperGeneratorWorker;
use SetBased\Stratum\Backend\StratumStyle;

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
  public function createRoutineWrapperGeneratorWorker(Config       $settings,
                                                      StratumStyle $io): ?RoutineWrapperGeneratorWorker
  {
    return new SqlitePdoRoutineWrapperGeneratorWorker($settings, $io);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
