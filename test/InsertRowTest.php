<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for inserting a row.
 */
class InsertRowTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Simple test for inserting a row.
   */
  public function test1()
  {
    $this->dataLayer->executeNone('
create table TST_INSERT_ROW
(
  tst_id    integer not null primary key asc,
  tst_name  varchar not null,
  tst_float real,
  tst_text  text,
  tst_blob  blob
)');

    $this->dataLayer->insertRow('TST_INSERT_ROW', ['tst_id'    => 1,
                                                   'tst_name'  => 'name',
                                                   'tst_float' => pi(),
                                                   'tst_text'  => 'Hello, World!',
                                                   'tst_blob'  => hex2bin('00FF')]);

    $id = $this->dataLayer->lastInsertId();
    self::assertSame(1, $id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Simple test for inserting multiple rows.
   */
  public function test2()
  {
    $this->dataLayer->executeNone('
create table TST_INSERT_ROW
(
  tst_id    integer not null primary key asc,
  tst_name  varchar not null,
  tst_float real,
  tst_text  text,
  tst_blob  blob
)');

    $this->dataLayer->insertRows('TST_INSERT_ROW', [['tst_id'    => 1,
                                                     'tst_name'  => 'name1',
                                                     'tst_float' => pi(),
                                                     'tst_text'  => 'Hello, World!',
                                                     'tst_blob'  => hex2bin('00FF')],
                                                    ['tst_id'    => 2,
                                                     'tst_name'  => 'name2',
                                                     'tst_float' => exp(1.0),
                                                     'tst_text'  => 'foobar',
                                                     'tst_blob'  => hex2bin('00FF')]]);

    $id = $this->dataLayer->lastInsertId();
    self::assertSame(2, $id);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

