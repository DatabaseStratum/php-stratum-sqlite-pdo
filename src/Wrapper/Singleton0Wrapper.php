<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\SqlitePdo\Helper\DataTypeHelper;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0 or 1 row having a single column only.
 */
class Singleton0Wrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return $this->routine['return'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    $type = DataTypeHelper::phpTypeHintingToPhpTypeDeclaration($this->getDocBlockReturnType());

    if ($type==='') return '';

    return ': '.$type;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeResultHandler(): void
  {
    $this->codeStore->append('');
    if ($this->routine['return']=='bool')
    {
      $this->codeStore->append('return !empty($this->executeSingleton0($query));');
    }
    else
    {
      $this->codeStore->append('return $this->executeSingleton0($query);');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
