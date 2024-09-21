<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Exception\FallenException;
use SetBased\Stratum\Common\Wrapper\CommonWrapper;
use SetBased\Stratum\Common\Wrapper\Helper\WrapperContext;

/**
 * Abstract parent class for all wrapper generators.
 */
abstract class SqlitePdoWrapper extends CommonWrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A factory for creating the appropriate object for generating a wrapper method for a stored routine.
   *
   * @param WrapperContext $context The wrapper context.
   */
  public static function createRoutineWrapper(WrapperContext $context): SqlitePdoWrapper
  {
    $type = $context->phpStratumMetadata['designation']['type'];
    switch ($type)
    {
      case 'bulk':
        $wrapper = new BulkWrapper();
        break;

      case 'lastInsertId':
        $wrapper = new LastInsertIdWrapper();
        break;

      case 'none':
        $wrapper = new NoneWrapper();
        break;

      case 'row0':
        $wrapper = new Row0Wrapper();
        break;

      case 'row1':
        $wrapper = new Row1Wrapper();
        break;

      case 'rows':
        $wrapper = new RowsWrapper();
        break;

      case 'singleton0':
        $wrapper = new Singleton0Wrapper();
        break;

      case 'singleton1':
        $wrapper = new Singleton1Wrapper();
        break;

      default:
        throw new FallenException('routine type', $type);
    }

    return $wrapper;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function generateMethodBody(WrapperContext $context): void
  {
    if ($this->hasRoutineArgs($context))
    {
      $context->codeStore->append('$replace = '.$this->getRoutineArgs($context).';', false);
      $context->codeStore->append('$query   = <<< EOT');
    }
    else
    {
      $context->codeStore->append('$query = <<< EOT');
    }
    $context->codeStore->append(rtrim($context->phpStratumMetadata['source']));
    $context->codeStore->append('EOT;', false);
    $context->codeStore->append('$query = str_repeat(PHP_EOL, '.$context->phpStratumMetadata['offset'].').$query;');
    $this->generateResultHandler($context);
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates code for calling the stored routine in the wrapper method.
   *
   * @param WrapperContext $context The wrapper context.
   */
  abstract protected function generateResultHandler(WrapperContext $context): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns code for the arguments for calling the stored routine in a wrapper method.
   *
   * @param WrapperContext $context The wrapper context.
   */
  private function getRoutineArgs(WrapperContext $context): string
  {
    $tmp = [];
    foreach ($context->phpStratumMetadata['parameters'] as $parameter)
    {
      $mangledName = $context->mangler::getParameterName($parameter['parameter_name']);
      $tmp[]       = sprintf("':%s' => %s",
                             $parameter['parameter_name'],
                             $context->dataType->escapePhpExpression($parameter, '$'.$mangledName));
    }

    return sprintf('[%s]', implode(sprintf(',%s%s', PHP_EOL, str_repeat(' ', mb_strlen('    $replace = ['))), $tmp));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
