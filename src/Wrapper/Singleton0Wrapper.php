<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\Common\Wrapper\Helper\WrapperContext;
use SetBased\Stratum\SqlitePdo\Helper\SqlitePdoDataTypeHelper;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0 or 1 row having a single column only.
 */
class Singleton0Wrapper extends SqlitePdoWrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function generateResultHandler(WrapperContext $context): void
  {
    $context->codeStore->append('');

    if ($context->phpStratumMetadata['designation']['return']===['bool'])
    {
      if ($this->hasRoutineArgs($context))
      {
        $context->codeStore->append('return !empty($this->executeSingleton0($query, $replace));');
      }
      else
      {
        $context->codeStore->append('return !empty($this->executeSingleton0($query));');
      }
    }
    else
    {
      if ($this->hasRoutineArgs($context))
      {
        $context->codeStore->append('return $this->executeSingleton0($query, $replace);');
      }
      else
      {
        $context->codeStore->append('return $this->executeSingleton0($query);');
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(WrapperContext $context): string
  {
    return implode('|', $context->phpStratumMetadata['designation']['return']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(WrapperContext $context): string
  {
    $type = SqlitePdoDataTypeHelper::phpTypeHintingToPhpTypeDeclaration($this->getDocBlockReturnType($context));
    if ($type==='')
    {
      return '';
    }

    return ': '.$type;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
