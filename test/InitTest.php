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
   * Test in memory database.
   */
  public function testInMemory()
  {
    $dl      = new SqlitePdoDataLayer();
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with path.
   */
  public function testPath()
  {
    $path    = __DIR__.'/test.db';
    $dl      = new SqlitePdoDataLayer($path);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertRegExp('/^[0-9.]+$/', $version);

    unlink($path);
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

