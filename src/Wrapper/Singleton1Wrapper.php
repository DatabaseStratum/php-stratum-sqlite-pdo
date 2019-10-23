<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

use SetBased\Stratum\SqlitePdo\Helper\DataTypeHelper;

/**
 * Class for generating a wrapper method for a stored procedure that selects 1 row having a single column only.
 */
class Singleton1Wrapper extends Wrapper
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
      if ($this->hasRoutineArgs())
      {
        $this->codeStore->append('return !empty($this->executeSingleton1($query, $replace));');
      }
      else
      {
        $this->codeStore->append('return !empty($this->executeSingleton1($query));');
      }
    }
    else
    {
      if ($this->hasRoutineArgs())
      {
        $this->codeStore->append('return $this->executeSingleton1($query, $replace);');
      }
      else
      {
        $this->codeStore->append('return $this->executeSingleton1($query);');
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
