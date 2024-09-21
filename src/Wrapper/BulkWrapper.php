<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\Common\Wrapper\Helper\WrapperContext;
use SetBased\Stratum\Middle\BulkHandler;

/**
 * Class for generating a wrapper method for a stored routine selecting a large number of rows.
 */
class BulkWrapper extends SqlitePdoWrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function enhancePhpDocBlockParameters(array &$parameters): void
  {
    $this->imports[] = BulkHandler::class;

    $parameter = ['php_name'       => '$bulkHandler',
                  'description'    => 'The bulk row handler.',
                  'php_type'       => 'BulkHandler',
                  'dtd_identifier' => null];

    $parameters = array_merge([$parameter], $parameters);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function generateResultHandler(WrapperContext $context): void
  {
    $context->codeStore->append('');
    if ($this->hasRoutineArgs($context))
    {
      $context->codeStore->append('$this->executeBulk($bulkHandler, $query, $replace);');
    }
    else
    {
      $context->codeStore->append('$this->executeBulk($bulkHandler, $query);');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(WrapperContext $context): string
  {
    return 'void';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(WrapperContext $context): string
  {
    return ': void';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
