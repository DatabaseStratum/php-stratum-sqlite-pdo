<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

/**
 * Class for generating a wrapper method for a stored procedure that insert rows in a table with an auto increment key.
 */
class LastInsertIdWrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'int';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    return ': int';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeResultHandler(): void
  {
    $this->codeStore->append('$this->executeNone($query);');
    $this->codeStore->append('');
    $this->codeStore->append('return $this->lastInsertId();');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
