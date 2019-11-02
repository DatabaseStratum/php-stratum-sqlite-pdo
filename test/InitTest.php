<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use SetBased\Stratum\SqlitePdo\SqlitePdoDataLayer;

/**
 * Test cases for multi query statements.
 */
class InitTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test in memory database without script.
   */
  public function testInMemory1()
  {
    $dl      = new SqlitePdoDataLayer();
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test in memory database with script.
   */
  public function testInMemory2()
  {
    $dl      = new SqlitePdoDataLayer(null, 'test/ddl/0100_create_tables.sql');
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with path.
   */
  public function testPath1()
  {
    $path    = __DIR__.'/test.db';
    $dl      = new SqlitePdoDataLayer($path);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);

    unlink($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with path and volatile.
   */
  public function testPath2()
  {
    $path    = __DIR__.'/test.db';
    $dl      = new SqlitePdoDataLayer($path, 'test/ddl/0100_create_tables.sql', true);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);
    $dl->close();
    self::assertFileNotExists($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *Test constructor with path, volatile, and database exists.
   */
  public function testPath3()
  {
    $path    = __DIR__.'/test.db';
    file_put_contents($path, __METHOD__);
    $dl      = new SqlitePdoDataLayer($path, 'test/ddl/0100_create_tables.sql', true);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);
    $dl->close();
    self::assertFileNotExists($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with illegal path.
   */
  public function testPath4()
  {
    $this->expectException(\InvalidArgumentException::class);
    new SqlitePdoDataLayer('', 'test/ddl/0100_create_tables.sql', false);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with \PDO object.
   */
  public function testPdo()
  {
    $path    = __DIR__.'/test.db';
    $pdo     = new \PDO('sqlite:'.$path);
    $dl      = new SqlitePdoDataLayer($pdo);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);

    unlink($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with \PDO object to a MySQL connection.
   */
  public function testPdoMySql()
  {
    $pdo = new \PDO('mysql:hosts=localhost;dbname=test', 'test', 'test');

    $this->expectException(\InvalidArgumentException::class);
    new SqlitePdoDataLayer($pdo);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with integer.
   */
  public function testInt()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('A integer is not a valid argument.');
    new SqlitePdoDataLayer(123);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with an object that is not a \PDO object.
   */
  public function testObject()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('A SetBased\Stratum\SqlitePdo\Test\InitTest is not a valid argument.');
    new SqlitePdoDataLayer($this);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

