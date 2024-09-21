<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Loader;

use SetBased\Stratum\Backend\StratumStyle;
use SetBased\Stratum\Common\Loader\CommonRoutineLoader;
use SetBased\Stratum\Common\Loader\Helper\LoaderContext;
use SetBased\Stratum\SqlitePdo\Loader\Helper\RoutineParametersHelper;
use SetBased\Stratum\SqlitePdo\SqlitePdoDataLayer;

/**
 * Class for mimicking loading a single stored routine into a SQLite instance from pseudo SQL file.
 */
class SqlitePdoRoutineLoader extends CommonRoutineLoader
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An in memory SQLite database.
   *
   * @var SqlitePdoDataLayer
   */
  private SqlitePdoDataLayer $dl;

  /**
   * The offset of the first line of the payload of the stored routine ins the source file.
   *
   * @var int
   */
  private int $offset;

  /**
   * The payload of the stored routine (i.e. the code without the DocBlock).
   *
   * @var string
   */
  private string $routinePayLoad;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param StratumStyle $io The output for log messages.
   */
  public function __construct(StratumStyle $io)
  {
    parent::__construct($io);

    $this->dl = new SqlitePdoDataLayer();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function extractInsertMultipleTableColumns(LoaderContext $context): void
  {
    // TODO: Implement extractInsertManyTableColumns() method.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function extractName(LoaderContext $context): void
  {
    $context->storedRoutine->setType('procedure');
    $context->storedRoutine->setName(pathinfo($context->storedRoutine->getPath(), PATHINFO_FILENAME));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function extractStoredRoutineParameters(LoaderContext $context): void
  {
    $routineParametersHelper = new RoutineParametersHelper();
    $context->storedRoutine->setParameters($routineParametersHelper->getParameters($context));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadRoutineFile(LoaderContext $context): void
  {
    $this->offset         = $this->getFirstLineOfStoredRoutineBody($context) - 1;
    $this->routinePayLoad = implode(PHP_EOL, array_slice(explode(PHP_EOL, $this->routineSourceCode), $this->offset));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function updateMetadata(LoaderContext $context): void
  {
    parent::updateMetadata($context);

    $context->newPhpStratumMetadata['offset'] = $this->offset;
    $context->newPhpStratumMetadata['source'] = $this->routinePayLoad;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the first line of the body of the stored routine.
   *
   * @param LoaderContext $context The loader context.
   *
   * @return int
   */
  private function getFirstLineOfStoredRoutineBody(LoaderContext $context): int
  {
    $start = null;
    $last  = null;
    foreach ($context->storedRoutine->getCodeLines() as $i => $line)
    {
      if (trim($line)=='/**' && $start===null)
      {
        $start = $i + 1;
      }

      if (trim($line)=='*/' && $start!==null && $last===null)
      {
        $last = $i + 1;
        break;
      }
    }

    return ($last ?? 0) + 1;
  }
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
