<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Backend;

use SetBased\Stratum\Common\Backend\CommonRoutineLoaderWorker;
use SetBased\Stratum\Common\Helper\CommonDataTypeHelper;
use SetBased\Stratum\Common\Loader\CommonRoutineLoader;
use SetBased\Stratum\Common\Loader\Helper\EscapeHelper;
use SetBased\Stratum\Common\Loader\Helper\LoaderContext;
use SetBased\Stratum\SqlitePdo\Helper\SqlitePdoDataTypeHelper;
use SetBased\Stratum\SqlitePdo\Loader\Helper\SqlitePdoEscapeHelper;
use SetBased\Stratum\SqlitePdo\Loader\SqlitePdoRoutineLoader;
use SetBased\Stratum\SqlitePdo\SqlitePdoDataLayer;

/**
 * Command for mimicking loading stored routines into a SQLite instance from pseudo SQL files.
 */
class SqlitePdoRoutineLoaderWorker extends CommonRoutineLoaderWorker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The metadata layer.
   *
   * @var SqlitePdoDataLayer|null
   */
  protected ?SqlitePdoDataLayer $dl = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function connect(): void
  {
    $this->dl = new SqlitePdoDataLayer();
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
  /**
   * @inheritdoc
   */
  protected function createEscaper(): EscapeHelper
  {
    return new SqlitePdoEscapeHelper($this->dl);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createRoutineLoader(LoaderContext $context): CommonRoutineLoader
  {
    $context->docBlock->setParamTagsHaveTypes(true);

    return new SqlitePdoRoutineLoader($this->io);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function disconnect(): void
  {
    if ($this->dl!==null)
    {
      $this->dl->close();
      $this->dl = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function dropStoredRoutine(array $rdbmsMetadata): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function fetchColumnTypes(): void
  {
    // TODO: Implement fetchColumnTypes() method.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function fetchRdbmsMetadata(): array
  {
    $routines = [];
    try
    {
      $path     = $this->config->manString('loader.metadata');
      $metadata = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
      if ($metadata['php_stratum_metadata_revision']===$this->phpStratumMetadataRevision())
      {
        foreach ($metadata['stored_routines'] as $routineName => $routineData)
        {
          $routines[$routineName] = ['routine_name' => $routineName,
                                     'routine_type' => 'procedure'];
        }
      }
    }
    catch (\Throwable)
    {
      $routines = [];
    }

    return $routines;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function phpStratumMetadataRevision(): string
  {
    return parent::phpStratumMetadataRevision().'.1';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
