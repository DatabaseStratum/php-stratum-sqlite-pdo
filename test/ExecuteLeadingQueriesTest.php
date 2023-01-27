<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Test;

/**
 * Test cases method executeLeadingQueries.
 */
class ExecuteLeadingQueriesTest extends DataLayerTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Trailing comment must be ignored.
   */
  public function testIgnoreTrainingComment(): void
  {
    $text = $this->dataLayer->tstTestExecuteLeadingQueries();
    self::assertEquals('Hello, world!', $text);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

