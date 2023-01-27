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
  public function testInMemory1(): void
  {
    $dl      = new SqlitePdoDataLayer();
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test in memory database with script.
   */
  public function testInMemory2(): void
  {
    $dl      = new SqlitePdoDataLayer(null, 'test/ddl/0100_create_tables.sql');
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with path.
   */
  public function testPath1(): void
  {
    $path    = __DIR__.'/test.db';
    $dl      = new SqlitePdoDataLayer($path);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);

    unlink($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with path (file does not exist) and volatile.
   */
  public function testPath2a(): void
  {
    $path    = __DIR__.'/test.db';
    $dl      = new SqlitePdoDataLayer($path, 'test/ddl/0100_create_tables.sql', true);

    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);

    $count = $dl->executeSingleton1('select count(*) from TST_FOO1');
    self::assertSame(0, $count);

    $dl->close();
    self::assertFileDoesNotExist($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with path (file does exist) and volatile.
   */
  public function testPath2b(): void
  {
    $path    = __DIR__.'/test.db';
    touch($path);
    $dl      = new SqlitePdoDataLayer($path, 'test/ddl/0100_create_tables.sql', true);

    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);

    $count = $dl->executeSingleton1('select count(*) from TST_FOO1');
    self::assertSame(0, $count);

    $dl->close();
    self::assertFileDoesNotExist($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *Test constructor with path, volatile, and database exists.
   */
  public function testPath3(): void
  {
    $path    = __DIR__.'/test.db';
    file_put_contents($path, __METHOD__);
    $dl      = new SqlitePdoDataLayer($path, 'test/ddl/0100_create_tables.sql', true);

    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);


    $count = $dl->executeSingleton1('select count(*) from TST_FOO1');
    self::assertSame(0, $count);

    $dl->close();
    self::assertFileDoesNotExist($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with illegal path.
   */
  public function testPath4(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new SqlitePdoDataLayer('', 'test/ddl/0100_create_tables.sql', false);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with \PDO object.
   */
  public function testPdo(): void
  {
    $path    = __DIR__.'/test.db';
    $pdo     = new \PDO('sqlite:'.$path);
    $dl      = new SqlitePdoDataLayer($pdo);
    $version = $dl->executeSingleton1('select sqlite_version()');
    self::assertMatchesRegularExpression('/^[0-9.]+$/', $version);

    unlink($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with \PDO object to a MySQL connection.
   */
  public function testPdoMySql(): void
  {
    $pdo = new \PDO('mysql:host=127.0.0.1;dbname=test', 'test', 'test');

    $this->expectException(\InvalidArgumentException::class);
    new SqlitePdoDataLayer($pdo);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with integer.
   */
  public function testInt(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('A integer is not a valid argument.');
    new SqlitePdoDataLayer(123);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with an object that is not a \PDO object.
   */
  public function testObject(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('A SetBased\Stratum\SqlitePdo\Test\InitTest is not a valid argument.');
    new SqlitePdoDataLayer($this);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

