<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\Common\Wrapper\Helper\WrapperContext;

/**
 * Class for generating a wrapper method for a stored procedure that insert rows in a table with an auto increment key.
 */
class LastInsertIdWrapper extends SqlitePdoWrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function generateResultHandler(WrapperContext $context): void
  {
    $context->codeStore->append('');
    if ($this->hasRoutineArgs($context))
    {
      $context->codeStore->append('$this->executeNone($query, $replace);');
    }
    else
    {
      $context->codeStore->append('$this->executeNone($query);');
    }
    $context->codeStore->append('return $this->lastInsertId();');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(WrapperContext $context): string
  {
    return 'int';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(WrapperContext $context): string
  {
    return ': int';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
