<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Backend;

use SetBased\Stratum\Common\Backend\CommonRoutineWrapperGeneratorWorker;
use SetBased\Stratum\Common\Helper\CommonDataTypeHelper;
use SetBased\Stratum\Common\Wrapper\Helper\WrapperContext;
use SetBased\Stratum\SqlitePdo\Helper\SqlitePdoDataTypeHelper;
use SetBased\Stratum\SqlitePdo\Wrapper\SqlitePdoWrapper;

/**
 * Command for generating a class with wrapper methods for calling stored routines in a SQLite database.
 */
class SqlitePdoRoutineWrapperGeneratorWorker extends CommonRoutineWrapperGeneratorWorker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function buildRoutineWrapper(WrapperContext $context): void
  {
    $wrapper = SqlitePdoWrapper::createRoutineWrapper($context);
    $wrapper->generateMethod($context);

    $this->imports = array_merge($this->imports, $wrapper->getImports());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createDataTypeHelper(): CommonDataTypeHelper
  {
    return new SqlitePdoDataTypeHelper();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
