<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Helper;

use SetBased\Exception\FallenException;

/**
 * Utility class for deriving information based on a SQLite data type.
 */
class DataTypeHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the corresponding PHP type hinting of a parameter type as specified in a DocBlock.
   *
   * @param string
   *
   * @return string
   */
  public static function columnTypeToPhpTypeHinting(string $dataType): string
  {
    switch ($dataType)
    {
      case 'bool':
      case 'boolean':
        $phpType = 'bool';
        break;

      case 'int':
      case 'integer':
        $phpType = 'int';
        break;

      case 'float':
      case 'double':
      case 'real':
        $phpType = 'float';
        break;

      case 'varchar':
      case 'string':
      case 'text':
      case 'blob':
        $phpType = 'string';
        break;

      default:
        throw new FallenException('data type', $dataType);
    }

    return $phpType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns PHP code escaping the value of a PHP expression that can be safely used when concatenating a SQL statement.
   *
   * @param string $dataType   The data type.
   * @param string $expression The PHP expression.
   *
   * @return string The generated PHP code.
   */
  public static function escapePhpExpression(string $dataType, string $expression): string
  {
    switch ($dataType)
    {
      case 'int':
      case 'integer':
        $ret = '$this->quoteInt('.$expression.")";
        break;

      case 'float':
      case 'double':
      case 'real':
        $ret = '$this->quoteReal('.$expression.")";
        break;

      case 'varchar':
      case 'string':
      case 'text':
        $ret = '$this->quoteVarchar('.$expression.")";
        break;

      case 'blob':
        $ret = '$this->quoteBlob('.$expression.")";
        break;

      default:
        throw new FallenException('data type', $dataType);
    }

    return $ret;
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the corresponding PHP type declaration of a SQLite column type.
   *
   * @param string $phpTypeHint The PHP type hinting.
   *
   * @return string
   */
  public static function phpTypeHintingToPhpTypeDeclaration(string $phpTypeHint): string
  {
    $phpType = '';

    switch ($phpTypeHint)
    {
      case 'array':
      case 'array[]':
      case 'bool':
      case 'float':
      case 'int':
      case 'string':
      case 'void':
        $phpType = $phpTypeHint;
        break;

      default:
        $parts = explode('|', $phpTypeHint);
        $key   = array_search('null', $parts);
        if (sizeof($parts)==2 && $key!==false)
        {
          unset($parts[$key]);

          $tmp = static::phpTypeHintingToPhpTypeDeclaration(implode('|', $parts));
          if ($tmp!=='')
          {
            $phpType = '?'.$tmp;
          }
        }
    }

    return $phpType;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
