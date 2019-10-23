<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0, 1, or more rows.
 */
class RowsWrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'array[]';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    return ': array';
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
      $this->codeStore->append('return $this->executeRows($query, $replace);');
    }
    else
    {
      $this->codeStore->append('return $this->executeRows($query);');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
