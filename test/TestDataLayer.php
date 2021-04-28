<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\Middle\BulkHandler;
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
select '/opt/Projects/DatabaseStratum/php-stratum-sqlite-pdo/test/psql/tst_magic_constant03.psql';
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
select '/opt/Projects/DatabaseStratum/php-stratum-sqlite-pdo/test/psql';
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
select '/opt/Projects/DatabaseStratum/php-stratum-sqlite-pdo/test/psql/ test_escape '' " @ $ ! .';
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
    $query = str_repeat(PHP_EOL, 9).$query;

    return $this->executeRow1($query, $replace);
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
    $replace = [':p_tst_int' => $this->quoteInt($pTstInt), ':p_tst_real' => $this->quoteReal($pTstReal), ':p_tst_text' => $this->quoteVarchar($pTstText), ':p_tst_blob' => $this->quoteBlob($pTstBlob)];
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
    $query = str_repeat(PHP_EOL, 10).$query;

    $this->executeNone($query, $replace);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type bulk.
   *
   * @param BulkHandler $bulkHandler The bulk row handler
   * @param int|null    $pCount      The number of rows selected.
   *
   * @return void
   */
  public function tstTestBulk(BulkHandler $bulkHandler, ?int $pCount): void
  {
    $replace = [':p_count' => $this->quoteInt($pCount)];
    $query   = <<< EOT
select *
from TST_FOO2
where tst_c00 <= :p_count
EOT;
    $query = str_repeat(PHP_EOL, 7).$query;

    $this->executeBulk($bulkHandler, $query, $replace);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test comment add end is ignored.
   *
   * @return string
   */
  public function tstTestExecuteLeadingQueries(): string
  {
    $query = <<< EOT
create temporary table TMP_FOO(x int);

create index TMP_IDX01 on TMP_FOO(x);

select 'Hello, world!';

/**
 * This is a trailing comment.
 */
EOT;
    $query = str_repeat(PHP_EOL, 6).$query;

    return $this->executeSingleton1($query);
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
drop table if exists TST_FOOBAR;

drop table if exists TST_FOOBAR;

select * from NOT_EXISTS;
EOT;
    $query = str_repeat(PHP_EOL, 5).$query;

    return $this->executeRows($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test case for designation type lastIncrementId.
   *
   * @param string|null $pTstTest Some value.
   *
   * @return int
   */
  public function tstTestLastIncrementId(?string $pTstTest): int
  {
    $replace = [':p_tst_test' => $this->quoteVarchar($pTstTest)];
    $query   = <<< EOT
insert into TST_LAST_INCREMENT_ID( tst_test )
values( :p_tst_test )
EOT;
    $query = str_repeat(PHP_EOL, 7).$query;

    $this->executeNone($query, $replace);
    return $this->lastInsertId();
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
   * @param int|null $pCount The number of rows selected.
   *                         * 0 For a valid test.
   *                         * 1 For a valid test.
   *                         * 2 For a invalid test.
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
    $query = str_repeat(PHP_EOL, 10).$query;

    return $this->executeRow0($query, $replace);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type rows and type conversion.
   *
   * @return array
   */
  public function tstTestRow1Conversion(): array
  {
    $query = <<< EOT
select cast(1 as int)             as c_int
,      cast(1.1 as numeric)       as c_numeric
,      cast(2.2 as float)         as c_float
,      cast(3.3 as real)          as c_real
,      cast(4.4 as double)        as c_double
,      cast('varchar' as varchar) as c_varchar
,      cast('text' as text)       as c_text
,      cast('blob' as blob)       as c_blob
EOT;
    $query = str_repeat(PHP_EOL, 5).$query;

    return $this->executeRow1($query);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type row1.
   *
   * @param int|null $pCount The number of rows selected.
   *                         * 0 For a invalid test.
   *                         * 1 For a valid test.
   *                         * 2 For a invalid test.
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
    $query = str_repeat(PHP_EOL, 10).$query;

    return $this->executeRow1($query, $replace);
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
    $query = str_repeat(PHP_EOL, 7).$query;

    return $this->executeRows($query, $replace);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton0.
   *
   * @param int|null $pCount The number of rows selected.
   *                         * 0 For a valid test.
   *                         * 1 For a valid test.
   *                         * 2 For a invalid test.
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
    $query = str_repeat(PHP_EOL, 11).$query;

    return $this->executeSingleton0($query, $replace);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton0 with return type bool.
   *
   * @param int|null $pCount The number of rows selected.
   *                         * 0 For a valid test.
   *                         * 1 For a valid test.
   *                         * 2 For a invalid test.
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
    $query = str_repeat(PHP_EOL, 12).$query;

    return !empty($this->executeSingleton0($query, $replace));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton1.
   *
   * @param int|null $pCount The number of rows selected.
   *                         * 0 For a invalid test.
   *                         * 1 For a valid test.
   *                         * 2 For a invalid test.
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
    $query = str_repeat(PHP_EOL, 11).$query;

    return $this->executeSingleton1($query, $replace);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for designation type singleton1 with return type bool.
   *
   * @param int|null $pCount The number of rows selected.
   *                         * 0 For a invalid test.
   *                         * 1 For a valid test.
   *                         * 2 For a invalid test.
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
    $query = str_repeat(PHP_EOL, 12).$query;

    return !empty($this->executeSingleton1($query, $replace));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
