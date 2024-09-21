<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Loader\Helper;

use SetBased\Stratum\Common\Loader\Helper\EscapeHelper;
use SetBased\Stratum\SqlitePdo\SqlitePdoDataLayer;

/**
 * Object for escaping strings such that they are safe to use in SQL queries.
 */
class SqlitePdoEscapeHelper implements EscapeHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The connection to the MySQL or MariaDB instance.
   *
   * @var SqlitePdoDataLayer
   */
  private SqlitePdoDataLayer $dl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct(SqlitePdoDataLayer $dl)
  {
    $this->dl = $dl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function escapeString(string $string): string
  {
    return mb_substr($this->dl->quoteText($string), 1, -1);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
