<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Helper;

use SetBased\Stratum\Backend\StratumStyle;
use SetBased\Stratum\Common\DocBlock\DocBlockReflection;
use SetBased\Stratum\Common\Exception\RoutineLoaderException;
use SetBased\Stratum\Middle\Helper\RowSetHelper;

/**
 * Class for handling routine parameters.
 */
class RoutineParametersHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The DocBlock reflection object.
   *
   * @var DocBlockReflection
   */
  private $docBlockReflection;

  /**
   * The Output decorator.
   *
   * @var StratumStyle
   */
  private $io;

  /**
   * The offset of the first line of the payload of the stored routine ins the source file.
   *
   * @var int
   */
  private $offset;

  /**
   * The information about the parameters of the stored routine.
   *
   * @var array[]
   */
  private $routineParameters = [];

  /**
   * The source code as an array of lines string of the stored routine.
   *
   * @var array
   */
  private $routineSourceCodeLines;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param StratumStyle       $io                     The Output decorator.
   * @param DocBlockReflection $docBlockReflection     The DocBlock reflection object.
   * @param int                $offset                 The offset of the first line of the payload of the stored
   *                                                   routine ins the source file.
   * @param array              $routineSourceCodeLines The source code as an array of lines string of the stored
   *                                                   routine.
   */
  public function __construct(StratumStyle $io,
                              DocBlockReflection $docBlockReflection,
                              int $offset,
                              array $routineSourceCodeLines)
  {
    $this->io                     = $io;
    $this->docBlockReflection     = $docBlockReflection;
    $this->offset                 = $offset;
    $this->routineSourceCodeLines = $routineSourceCodeLines;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts DocBlock parts of the stored routine parameters to be used by the wrapper generator.
   *
   * @return array
   */
  public function extractDocBlockPartsWrapper(): array
  {
    $parameters = [];
    foreach ($this->docBlockReflection->getTags('param') as $parameter)
    {
      $type         = DataTypeHelper::columnTypeToPhpTypeHinting($parameter['arguments']['type']).'|null';
      $parameters[] = ['name'        => $parameter['arguments']['name'],
                       'php_type'    => $type,
                       'description' => $parameter['description']];
    }

    return $parameters;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts info about the parameters of the stored routine.
   *
   * @throws RoutineLoaderException
   */
  public function extractRoutineParameters(): void
  {
    $this->extractRoutineParametersInfo();
    $this->validateParameterLists();
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the metadata of of all parameters.
   *
   * @return array[]
   */
  public function getParameters(): array
  {
    $parameters = [];
    foreach ($this->docBlockReflection->getTags('param') as $parameter)
    {
      $parameters[$parameter['arguments']['name']] = ['name' => $parameter['arguments']['name'],
                                                      'type' => $parameter['arguments']['type']];
    }

    return $parameters;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts info about the parameters of the stored routine.
   */
  private function extractRoutineParametersInfo(): void
  {
    $body = implode(PHP_EOL, array_slice($this->routineSourceCodeLines, $this->offset - 1));

    preg_match_all('/(?<name>:[a-zA-Z_][a-zA-Z0-9_]*)/', $body, $matches);

    foreach ($matches['name'] as $name)
    {
      $this->routineParameters[$name] = ['name' => $name,
                                         'type' => $this->parameterType($name)];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of a parameter as found in the DocBlock of the stored routine.
   *
   * @param string $name The name of the parameter.
   *
   * @return string
   */
  private function parameterType(string $name): string
  {
    $parameters = $this->docBlockReflection->getTags('param');
    foreach ($parameters as $index => $parameter)
    {
      if (($parameter['arguments']['name'] ?? null)===$name)
      {
        $key = $index;
        break;
      }
    }

    return $parameters[$key]['arguments']['type'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates the parameters found the DocBlock in the source of the stored routine against the parameters from the
   * metadata of MySQL and reports missing and unknown parameters names.
   */
  private function validateParameterLists(): void
  {
    // Make list with names of parameters used in database.
    $databaseParametersNames = [];
    foreach ($this->routineParameters as $parameter)
    {
      $databaseParametersNames[] = $parameter['name'];
    }

    // Make list with names of parameters used in dock block of routine.
    $docBlockParametersNames = [];
    foreach ($this->routineParameters as $parameter)
    {
      $docBlockParametersNames[] = $parameter['name'];
    }

    // Check and show warning if any parameters is missing in doc block.
    $tmp = array_diff($databaseParametersNames, $docBlockParametersNames);
    foreach ($tmp as $name)
    {
      $this->io->logNote('Parameter <dbo>%s</dbo> is missing from doc block', $name);
    }

    // Check and show warning if find unknown parameters in doc block.
    $tmp = array_diff($docBlockParametersNames, $databaseParametersNames);
    foreach ($tmp as $name)
    {
      $this->io->logNote('Unknown parameter <dbo>%s</dbo> found in doc block', $name);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
