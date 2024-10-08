<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test\Application;

use PHPUnit\Framework\TestCase;
use SetBased\Stratum\Frontend\Application\Stratum;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Test cases for the stratum application.
 */
class StratumApplicationTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testExecute()
  {
    $application = new Stratum();
    $application->setAutoExit(false);

    $tester = new ApplicationTester($application);
    $tester->run(['command'     => 'stratum',
                  'config file' => 'test/etc/stratum.ini'],
                 ['-vv']);

    echo $tester->getDisplay();

    self::assertSame(0, $tester->getStatusCode(), $tester->getDisplay());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
