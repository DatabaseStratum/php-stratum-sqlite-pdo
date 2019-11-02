<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Exception;

use SetBased\Stratum\Middle\Exception\QueryErrorException;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Exception thrown when the execution of SQLite query fails.
 */
class SqlitePdoQueryErrorException extends SqlitePdoDataLayerException implements QueryErrorException
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The failed query.
   *
   * @var string
   */
  protected $query;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $errno The error code value of the error.
   * @param string $error Description of the last error.
   * @param string $query The failed query.
   */
  public function __construct(string $errno, string $error, string $query)
  {
    parent::__construct($errno, $error, $query);

    $this->query = $query;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array with the lines of the SQL statement. The line where the error occurred will be styled.
   *
   * @param string $style The style for highlighting the line with error.
   *
   * @return array The lines of the SQL statement.
   */
  public function styledQuery(string $style = 'error'): array
  {
    // The format of a query error is: %s near '%s' at line %d
    $error_line = trim(strrchr($this->error, ' '));

    // Prepend each line with line number.
    $lines   = explode(PHP_EOL, $this->query);
    $digits  = ceil(log(sizeof($lines) + 1, 10));
    $format  = sprintf('%%%dd %%s', $digits);
    $message = [];
    foreach ($lines as $i => $line)
    {
      if (($i + 1)==$error_line)
      {
        $message[] = sprintf('<%s>'.$format.'</%s>', $style, $i + 1, OutputFormatter::escape($line), $style);
      }
      else
      {
        $message[] = sprintf($format, $i + 1, OutputFormatter::escape($line));
      }
    }

    return $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
