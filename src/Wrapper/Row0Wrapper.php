<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\Common\Wrapper\Helper\WrapperContext;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0 or 1 row.
 */
class Row0Wrapper extends SqlitePdoWrapper
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
      $context->codeStore->append('return $this->executeRow0($query, $replace);');
    }
    else
    {
      $context->codeStore->append('return $this->executeRow0($query);');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(WrapperContext $context): string
  {
    return 'array|null';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(WrapperContext $context): string
  {
    return ': ?array';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
