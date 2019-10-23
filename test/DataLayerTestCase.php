<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

use PHPUnit\Framework\TestCase;

/**
 * Parent class for all test cases.
 */
class DataLayerTestCase extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The data layer.
   *
   * @var TestDataLayer
   */
  protected $dataLayer;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the database.
   */
  public function setUp(): void
  {
    $this->dataLayer = new TestDataLayer();

    $this->dataLayer->executeNone(file_get_contents('test/ddl/0100_create_tables.sql'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Destroys the database.
   */
  public function tearDown(): void
  {
    $this->dataLayer = null;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
