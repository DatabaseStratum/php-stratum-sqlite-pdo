<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Wrapper;

/**
 * Class for generating a wrapper method for a stored procedure that selects 1 row.
 */
class Row1Wrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'array';
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
    $this->codeStore->append('return $this->executeRow1($query);');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
