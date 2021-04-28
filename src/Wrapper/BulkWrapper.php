<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\Middle\BulkHandler;

/**
 * Class for generating a wrapper method for a stored routine selecting a large number of rows.
 */
class BulkWrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function enhancePhpDocBlockParameters(array $parameters): array
  {
    $this->imports[] = BulkHandler::class;

    $parameter = ['php_name'    => '$bulkHandler',
                  'description' => ['The bulk row handler'],
                  'php_type'    => 'BulkHandler'];

    return array_merge([$parameter], $parameters);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function enhanceWrapperParameters(string $code): string
  {
    if ($code!=='')
    {
      $code = ', '.$code;
    }

    return 'BulkHandler $bulkHandler'.$code;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'void';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    return ': void';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeResultHandler(): void
  {
    $this->codeStore->append('');
    if ($this->hasRoutineArgs())
    {
      $this->codeStore->append('$this->executeBulk($bulkHandler, $query, $replace);');
    }
    else
    {
      $this->codeStore->append('$this->executeBulk($bulkHandler, $query);');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
