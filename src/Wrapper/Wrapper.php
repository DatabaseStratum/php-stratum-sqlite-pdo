<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Exception\FallenException;
use SetBased\Helper\CodeStore\PhpCodeStore;
use SetBased\Stratum\Middle\NameMangler\NameMangler;
use SetBased\Stratum\SqlitePdo\Helper\DataTypeHelper;

/**
 * Abstract parent class for all wrapper generators.
 */
abstract class Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The code store for the generated PHP code.
   *
   * @var PhpCodeStore
   */
  protected PhpCodeStore $codeStore;

  /**
   * Array with fully qualified names that must be imported for this wrapper method.
   *
   * @var array
   */
  protected array $imports = [];

  /**
   * The name mangler for wrapper and parameter names.
   *
   * @var NameMangler
   */
  protected NameMangler $nameMangler;

  /**
   * The metadata of the stored routine.
   *
   * @var array
   */
  protected array $routine;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param array        $routine     The metadata of the stored routine.
   * @param PhpCodeStore $codeStore   The code store for the generated code.
   * @param NameMangler  $nameMangler The mangler for wrapper and parameter names.
   */
  public function __construct(array $routine, PhpCodeStore $codeStore, NameMangler $nameMangler)
  {
    $this->routine     = $routine;
    $this->codeStore   = $codeStore;
    $this->nameMangler = $nameMangler;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A factory for creating the appropriate object for generating a wrapper method for a stored routine.
   *
   * @param array        $routine     The metadata of the stored routine.
   * @param PhpCodeStore $codeStore   The code store for the generated code.
   * @param NameMangler  $nameMangler The mangler for wrapper and parameter names.
   *
   * @return Wrapper
   */
  public static function createRoutineWrapper(array $routine, PhpCodeStore $codeStore, NameMangler $nameMangler): Wrapper
  {
    switch ($routine['designation'])
    {
      case 'bulk':
        $wrapper = new BulkWrapper($routine, $codeStore, $nameMangler);
        break;

      case 'lastInsertId':
        $wrapper = new LastInsertIdWrapper($routine, $codeStore, $nameMangler);
        break;

      case 'none':
        $wrapper = new NoneWrapper($routine, $codeStore, $nameMangler);
        break;

      case 'row0':
        $wrapper = new Row0Wrapper($routine, $codeStore, $nameMangler);
        break;

      case 'row1':
        $wrapper = new Row1Wrapper($routine, $codeStore, $nameMangler);
        break;

      case 'rows':
        $wrapper = new RowsWrapper($routine, $codeStore, $nameMangler);
        break;

      case 'singleton0':
        $wrapper = new Singleton0Wrapper($routine, $codeStore, $nameMangler);
        break;

      case 'singleton1':
        $wrapper = new Singleton1Wrapper($routine, $codeStore, $nameMangler);
        break;

      default:
        throw new FallenException('routine type', $routine['designation']);
    }

    return $wrapper;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array with fully qualified names that must be imported in the stored routine wrapper class.
   *
   * @return array
   */
  public function getImports(): array
  {
    return $this->imports;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a wrapper method for a stored routine.
   */
  public function writeRoutineFunction(): void
  {
    $wrapperArgs = $this->getWrapperArgs();
    $methodName  = $this->nameMangler->getMethodName($this->routine['routine_name']);
    $returnType  = $this->getReturnTypeDeclaration();

    $this->codeStore->appendSeparator();
    $this->generatePhpDoc();
    $this->codeStore->append('public function '.$methodName.'('.$wrapperArgs.')'.$returnType);
    $this->codeStore->append('{');
    if ($this->hasRoutineArgs())
    {
      $this->codeStore->append('$replace = '.$this->getRoutineArgs().';');
      $this->codeStore->append('$query   = <<< EOT');
    }
    else
    {
      $this->codeStore->append('$query = <<< EOT');
    }
    $this->codeStore->append(rtrim($this->routine['source']));
    $this->codeStore->append('EOT;', false);
    $this->codeStore->append('$query = str_repeat(PHP_EOL, '.$this->routine['offset'].').$query;');
    $this->writeResultHandler();
    $this->codeStore->append('}');
    $this->codeStore->append('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enhances the metadata of the parameters of the stored routine wrapper.
   *
   * @param array[] $parameters The metadata of the parameters. For each parameter the
   *                            following keys must be defined:
   *                            <ul>
   *                            <li> php_name       The name of the parameter (including $).
   *                            <li> description    The description of the parameter.
   *                            <li> php_type       The type of the parameter.
   *                            </ul>
   *
   * @return array[]
   */
  protected function enhancePhpDocBlockParameters(array $parameters): array
  {
    return $parameters;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enhances the code for the parameters of the wrapper method for the stored routine.
   *
   * @param string $code The code of the parameters of the stored routine wrapper.
   */
  protected function enhanceWrapperParameters(string $code): string
  {
    return $code;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the return type to be used in the DocBlock.
   *
   * @return string
   */
  abstract protected function getDocBlockReturnType(): string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the return type declaration of the wrapper method.
   *
   * @return string
   */
  abstract protected function getReturnTypeDeclaration(): string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns code for the parameters of the wrapper method for the stored routine.
   *
   * @return string
   */
  protected function getWrapperArgs(): string
  {
    $code = '';

    foreach ($this->routine['parameters'] as $parameter)
    {
      if ($code!=='') $code .= ', ';

      $dataType    = DataTypeHelper::columnTypeToPhpTypeHinting($parameter['type']);
      $declaration = DataTypeHelper::phpTypeHintingToPhpTypeDeclaration($dataType.'|null');
      if ($declaration!=='')
      {
        $code .= $declaration.' ';
      }

      $code .= '$'.$this->nameMangler->getParameterName(ltrim($parameter['name'], ':'));
    }

    return $this->enhanceWrapperParameters($code);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if the routines has arguments.
   *
   * @return bool
   */
  protected function hasRoutineArgs(): bool
  {
    return !empty($this->routine['phpdoc']['parameters']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates code for calling the stored routine in the wrapper method.
   *
   * @return void
   */
  abstract protected function writeResultHandler(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generate php doc block in the data layer for stored routine.
   */
  private function generatePhpDoc(): void
  {
    $this->codeStore->append('/**', false);

    // Generate phpdoc with short description of routine wrapper.
    $this->generatePhpDocShortDescription();

    // Generate phpdoc with long description of routine wrapper.
    $this->generatePhpDocLongDescription();

    // Generate phpDoc with parameters and descriptions of parameters.
    $this->generatePhpDocParameters();

    // Generate return parameter doc.
    $this->generatePhpDocBlockReturn();

    $this->codeStore->append(' */', false);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the PHP doc block for the return type of the stored routine wrapper.
   */
  private function generatePhpDocBlockReturn(): void
  {
    $return = $this->getDocBlockReturnType();
    if ($return!=='')
    {
      $this->codeStore->append(' *', false);
      $this->codeStore->append(' * @return '.$return, false);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the long description of stored routine wrapper.
   */
  private function generatePhpDocLongDescription(): void
  {
    if (!empty($this->routine['phpdoc']['long_description']))
    {
      foreach ($this->routine['phpdoc']['long_description'] as $line)
      {
        $this->codeStore->append(' * '.$line, false);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the doc block for parameters of stored routine wrapper.
   */
  private function generatePhpDocParameters(): void
  {
    $parameters = [];
    foreach ($this->routine['phpdoc']['parameters'] as $parameter)
    {
      $mangledName = $this->nameMangler->getParameterName(ltrim($parameter['name'], ':'));

      $parameters[] = ['php_name'    => '$'.$mangledName,
                       'description' => $parameter['description'],
                       'php_type'    => $parameter['php_type']];
    }
    $parameters = $this->enhancePhpDocBlockParameters($parameters);

    if (!empty($parameters))
    {
      // Compute the max lengths of parameter names and the PHP types of the parameters.
      $max_name_length = 0;
      $max_type_length = 0;
      foreach ($parameters as $parameter)
      {
        $max_name_length = max($max_name_length, mb_strlen($parameter['php_name']));
        $max_type_length = max($max_type_length, mb_strlen($parameter['php_type']));
      }

      $this->codeStore->append(' *', false);

      // Generate phpDoc for the parameters of the wrapper method.
      foreach ($parameters as $parameter)
      {
        $format = sprintf(' * %%-%ds %%-%ds %%-%ds %%s', mb_strlen('@param'), $max_type_length, $max_name_length);

        $lines = $parameter['description'];
        if (!empty($lines))
        {
          $line = array_shift($lines);
          $this->codeStore->append(sprintf($format, '@param', $parameter['php_type'], $parameter['php_name'], $line), false);
          foreach ($lines as $line)
          {
            $this->codeStore->append(sprintf($format, ' ', ' ', ' ', $line), false);
          }
        }
        else
        {
          $this->codeStore->append(sprintf($format, '@param', $parameter['php_type'], $parameter['php_name'], ''), false);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the short description of stored routine wrapper.
   */
  private function generatePhpDocShortDescription(): void
  {
    if (!empty($this->routine['phpdoc']['short_description']))
    {
      foreach ($this->routine['phpdoc']['short_description'] as $line)
      {
        $this->codeStore->append(' * '.$line, false);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns code for the arguments for calling the stored routine in a wrapper method.
   *
   * @return string
   */
  private function getRoutineArgs(): string
  {
    $ret = '[';

    foreach ($this->routine['phpdoc']['parameters'] as $parameter)
    {
      $mangledName = $this->nameMangler->getParameterName(ltrim($parameter['name'], ':'));
      $type        = $this->routine['parameters'][$parameter['name']]['type'];

      if ($ret!='[') $ret .= ', ';
      $ret .= "'".$parameter['name']."' => ".DataTypeHelper::escapePhpExpression($type, '$'.$mangledName);
    }
    $ret .= ']';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
