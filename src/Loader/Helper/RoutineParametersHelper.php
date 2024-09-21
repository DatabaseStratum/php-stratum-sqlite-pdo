<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Loader\Helper;

use SetBased\Stratum\Common\Loader\Helper\LoaderContext;

/**
 * Class for handling routine parameters.
 */
class RoutineParametersHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The information about the parameters of the stored routine.
   *
   * @var array[]|null
   */
  private ?array $parameters = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the metadata of all parameters.
   *
   * @param LoaderContext $context The loader context.
   */
  public function getParameters(LoaderContext $context): array
  {
    if ($this->parameters===null)
    {
      $this->extractRoutineParametersInfo($context);
    }

    return $this->parameters;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of parameter as found in the DocBlock of the stored routine.
   *   *
   *
   * @param LoaderContext $context The loader context.
   * @param string        $name    The name of the parameter.
   *
   * @return string
   */
  private function extractParameterType(LoaderContext $context, string $name): string
  {
    foreach ($context->docBlock->getParameters() as $parameter)
    {
      if ($parameter['name']===$name)
      {
        return $parameter['type'];
      }
    }

    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts info about the parameters of the stored routine.
   *
   * @param LoaderContext $context The loader context.
   */
  private function extractRoutineParametersInfo(LoaderContext $context): void
  {
    $this->parameters = [];

    $body = implode(PHP_EOL, array_slice($context->storedRoutine->getCodeLines(), 1)); //XXX
    preg_match_all('/:(?<name>[a-zA-Z_][a-zA-Z0-9_]*)/', $body, $matches);
    foreach ($matches['name'] as $name)
    {
      $this->parameters[$name] = ['parameter_name' => $name,
                                  'dtd_identifier' => $this->extractParameterType($context, $name),
                                  'data_type'      => $this->extractParameterType($context, $name)];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
