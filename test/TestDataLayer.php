<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\SqlitePdo\SqlitePdoDataLayer;

/**
 * The data layer.
 */
class TestDataLayer extends SqlitePdoDataLayer
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for magic constant.
   *
   * @return string
   */
  public function tstMagicConstant01(): string
  {
    $query = <<< EOT
select 'tst_magic_constant01';
EOT;
    $query = str_repeat(PHP_EOL, 6).$query;

    return $this->executeSingleton1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for magic constant.
   *
   * @return int
   */
  public function tstMagicConstant02(): int
  {
    $query = <<< EOT
select 7;
EOT;
    $query = str_repeat(PHP_EOL, 6).$query;

    return $this->executeSingleton1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for magic constant.
   *
   * @return string
   */
  public function tstMagicConstant03(): string
  {
    $query = <<< EOT
select '/home/water/Projects/SetBased/php-stratum-sqlite-pdo/test/psql/tst_magic_constant03.psql';
EOT;
    $query = str_repeat(PHP_EOL, 6).$query;

    return $this->executeSingleton1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for magic constant.
   *
   * @return string
   */
  public function tstMagicConstant04(): string
  {
    $query = <<< EOT
select '/home/water/Projects/SetBased/php-stratum-sqlite-pdo/test/psql';
EOT;
    $query = str_repeat(PHP_EOL, 6).$query;

    return $this->executeSingleton1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for magic constant.
   *
   * @return string
   */
  public function tstMagicConstant05(): string
  {
    $query = <<< EOT
select '/home/water/Projects/SetBased/php-stratum-sqlite-pdo/test/psql/ test_escape '' " @ $ ! .';
EOT;
    $query = str_repeat(PHP_EOL, 6).$query;

    return $this->executeSingleton1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test strtr does not mix up parameters with nearly same name.
   *
   * @param int|null $p1   Parameter of type int.
   * @param int|null $p100 Parameter of type int.
   * @param int|null $p10  Parameter of type int.
   *
   * @return array
   */
  public function tstStrtr(?int $p1, ?int $p100, ?int $p10): array
  {
    $replace = [':p1' => $this->quoteInt($p1), ':p100' => $this->quoteInt($p100), ':p10' => $this->quoteInt($p10)];
    $query   = <<< EOT
select :p1   as p1
,      :p100 as p100a
,      :p10  as p10
,      :p100 as p100b
EOT;
    $query = str_repeat(PHP_EOL, 9).strtr($query, $replace);

    return $this->executeRow1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for all possible types of parameters including BLOBs.
   *
   * @param int|null    $pTstInt  Parameter of type int.
   * @param float|null  $pTstReal Parameter of type real.
   * @param string|null $pTstText Parameter of type text.
   * @param string|null $pTstBlob Parameter of type blob.
   */
  public function tstTest01(?int $pTstInt, ?float $pTstReal, ?string $pTstText, ?string $pTstBlob): void
  {
    $replace = [':p_tst_int' => $this->quoteInt($pTstInt), ':p_tst_real' => $this->quoteFloat($pTstReal), ':p_tst_text' => $this->quoteString($pTstText), ':p_tst_blob' => $this->quoteBinary($pTstBlob)];
    $query   = <<< EOT
insert into TST_FOO1( tst_int
,                     tst_real
,                     tst_text
,                     tst_blob )
values( :p_tst_int
,       :p_tst_real
,       :p_tst_text
,       :p_tst_blob )
EOT;
    $query = str_repeat(PHP_EOL, 10).strtr($query, $replace);

    $this->executeNone($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for illegal query.
   *
   * @return array[]
   */
  public function tstTestIllegalQuery(): array
  {
    $query = <<< EOT
select *
from DOES_NOT_EXISTS
EOT;
    $query = str_repeat(PHP_EOL, 5).$query;

    return $this->executeRows($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *
   * @return array
   */
  public function tstTestNoDocBlock(): array
  {
    $query = <<< EOT
select 'This SP is a test for sources without a DocBlock'
EOT;
    $query = str_repeat(PHP_EOL, 3).$query;

    return $this->executeRow1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type row0.
   *
   * @param int|null $pCount The number of rows selected. * 0 For a valid test. * 1 For a valid test. * 2 For a invalid test.
   *
   * @return array|null
   */
  public function tstTestRow0a(?int $pCount): ?array
  {
    $replace = [':p_count' => $this->quoteInt($pCount)];
    $query   = <<< EOT
select *
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 10).strtr($query, $replace);

    return $this->executeRow0($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type row1.
   *
   * @param int|null $pCount The number of rows selected. * 0 For a invalid test. * 1 For a valid test. * 2 For a invalid test.
   *
   * @return array
   */
  public function tstTestRow1a(?int $pCount): array
  {
    $replace = [':p_count' => $this->quoteInt($pCount)];
    $query   = <<< EOT
select *
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 10).strtr($query, $replace);

    return $this->executeRow1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type rows.
   *
   * @param int|null $pCount The number of rows selected.
   *
   * @return array[]
   */
  public function tstTestRows1(?int $pCount): array
  {
    $replace = [':p_count' => $this->quoteInt($pCount)];
    $query   = <<< EOT
select *
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 7).strtr($query, $replace);

    return $this->executeRows($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton0.
   *
   * @param int|null $pCount The number of rows selected. * 0 For a valid test. * 1 For a valid test. * 2 For a invalid test.
   *
   * @return int|null
   */
  public function tstTestSingleton0a(?int $pCount): ?int
  {
    $replace = [':p_count' => $this->quoteInt($pCount)];
    $query   = <<< EOT
select 1
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 11).strtr($query, $replace);

    return $this->executeSingleton0($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton0 with return type bool.
   *
   * @param int|null $pCount The number of rows selected. * 0 For a valid test. * 1 For a valid test. * 2 For a invalid test.
   * @param int|null $pValue The selected value.
   *
   * @return bool
   */
  public function tstTestSingleton0b(?int $pCount, ?int $pValue): bool
  {
    $replace = [':p_count' => $this->quoteInt($pCount), ':p_value' => $this->quoteInt($pValue)];
    $query   = <<< EOT
select :p_value
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 12).strtr($query, $replace);

    return !empty($this->executeSingleton0($query));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton1.
   *
   * @param int|null $pCount The number of rows selected. * 0 For a invalid test. * 1 For a valid test. * 2 For a invalid test.
   *
   * @return int
   */
  public function tstTestSingleton1a(?int $pCount): int
  {
    $replace = [':p_count' => $this->quoteInt($pCount)];
    $query   = <<< EOT
select 1
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 11).strtr($query, $replace);

    return $this->executeSingleton1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton1 with return type bool.
   *
   * @param int|null $pCount The number of rows selected. * 0 For a invalid test. * 1 For a valid test. * 2 For a invalid test.
   * @param int|null $pValue The selected value.
   *
   * @return bool
   */
  public function tstTestSingleton1b(?int $pCount, ?int $pValue): bool
  {
    $replace = [':p_count' => $this->quoteInt($pCount), ':p_value' => $this->quoteInt($pValue)];
    $query   = <<< EOT
select :p_value
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 12).strtr($query, $replace);

    return !empty($this->executeSingleton1($query));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
