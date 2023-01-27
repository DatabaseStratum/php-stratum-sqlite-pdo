<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases for magic constants.
 */
class MagicConstantTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constant __ROUTINE__. Must return name of routine.
   */
  public function test1(): void
  {
    $ret = $this->dataLayer->tstMagicConstant01();
    self::assertEquals('tst_magic_constant01', $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constant __LINE__. Must return line number in the source code.
   */
  public function test2(): void
  {
    $ret = $this->dataLayer->tstMagicConstant02();
    self::assertEquals(7, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constant __FILE__. Must return the filename of the source of the routine.
   */
  public function test3(): void
  {
    $filename = realpath(__DIR__.'/../test/psql/tst_magic_constant03.psql');

    $ret = $this->dataLayer->tstMagicConstant03();
    self::assertEquals($filename, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constant __DIR__. Must return name of the folder where the source file of routine the is located.
   */
  public function test4(): void
  {
    $dirname = realpath(__DIR__.'/../test/psql');

    $ret = $this->dataLayer->tstMagicConstant04();
    self::assertEquals($dirname, $ret);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constant __DIR__ with several characters that need escaping.
   */
  public function test5(): void
  {
    $dirname = realpath(__DIR__.'/../test/psql/ test_escape \' " @ $ ! .');
    if ($dirname)
    {
      $ret = $this->dataLayer->tstMagicConstant05();
      self::assertEquals($dirname, $ret);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
