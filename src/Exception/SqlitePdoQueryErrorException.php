<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Exception;

use SetBased\Stratum\Middle\Exception\QueryErrorException;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Exception thrown when the execution of MySQL query fails.
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
   * @param int    $errno  The error code value of the error ($mysqli->errno).
   * @param string $error  Description of the last error ($mysqli->error).
   * @param string $method The name of the executed method.
   * @param string $query  The failed query.
   */
  public function __construct(int $errno, string $error, string $method, string $query)
  {
    parent::__construct((string)$errno, $error, $method);

    $this->query = $query;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if this exception is caused by an invalid SQL statement. Otherwise returns false.
   *
   * @return bool
   */
  public function isQueryError(): bool
  {
    return ($this->code==1064);
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
    $query = trim($this->query); // MySQL ignores leading whitespace in queries.

    if ($this->isQueryError())
    {
      // Query is a multi line query.
      // The format of a 1064 message is: %s near '%s' at line %d
      $error_line = trim(strrchr($this->error, ' '));

      // Prepend each line with line number.
      $lines   = explode(PHP_EOL, $query);
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
    }
    else
    {
      $message = explode(PHP_EOL, $query);
    }

    return $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
