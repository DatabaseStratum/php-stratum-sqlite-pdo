<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Helper;

use SetBased\Exception\FallenException;
use SetBased\Stratum\Common\Helper\CommonDataTypeHelper;

/**
 * Utility class for deriving information based on a SQLite data type.
 */
class SqlitePdoDataTypeHelper implements CommonDataTypeHelper
{
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
        if (sizeof($parts)===2 && $key!==false)
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
  /**
   * @inheritdoc
   */
  public function allColumnTypes(): array
  {
    return [];
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the corresponding PHP type hinting of a parameter type as specified in a DocBlock.
   *
   * @param string[] $dataTypeInfo Metadata of the MySQL data type.
   */
  public function columnTypeToPhpType(array $dataTypeInfo): string
  {
    switch ($dataTypeInfo['data_type'])
    {
      case 'bool':
      case 'boolean':
        $phpType = 'bool';
        break;

      case 'int':
      case 'integer':
        $phpType = 'int';
        break;

      case 'real':
        $phpType = 'float';
        break;

      case 'text':
      case 'blob':
        $phpType = 'string';
        break;

      default:
        throw new FallenException('data type', $dataTypeInfo['data_type']);
    }

    return $phpType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns PHP code escaping the value of a PHP expression that can be safely used when concatenating a SQL statement.
   *
   * @param array  $dataTypeInfo Metadata of the column on which the field is based.
   * @param string $expression   The PHP expression.
   */
  public function escapePhpExpression(array $dataTypeInfo, string $expression): string
  {
    switch ($dataTypeInfo['data_type'])
    {
      case 'int':
      case 'integer':
        $ret = '$this->quoteInt('.$expression.")";
        break;

      case 'real':
        $ret = '$this->quoteReal('.$expression.")";
        break;

      case 'text':
        $ret = '$this->quoteText('.$expression.")";
        break;

      case 'blob':
        $ret = '$this->quoteBlob('.$expression.")";
        break;

      default:
        throw new FallenException('data type', $dataTypeInfo['data_type']);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
