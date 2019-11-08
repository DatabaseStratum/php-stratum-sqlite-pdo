<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Exception;

use SetBased\Stratum\Middle\Exception\DataLayerException;

/**
 * Exception for situations where the execution of a SQL query has failed.
 */
class SqlitePdoDataLayerException extends \RuntimeException implements DataLayerException
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The error code value of the error ($mysqli->errno).
   *
   * @var string
   */
  protected $code;

  /**
   * Description of the last error ($mysqli->error).
   *
   * @var string
   */
  protected $error;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string      $code  The error code value of the error ($mysqli->errno).
   * @param string      $error Description of the last error ($mysqli->error).
   * @param string|null $query The executed query.
   */
  public function __construct(string $code, string $error, string $query = null)
  {
    parent::__construct(self::message($code, $error, $query));

    $this->code  = $code;
    $this->error = $error;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Composes the exception message.
   *
   * @param string      $code  The error code value of the error ($mysqli->errno).
   * @param string      $error Description of the error ($mysqli->error).
   * @param string|null $query The executed query.
   *
   * @return string
   */
  private static function message(string $code, string $error, ?string $query): string
  {
    $message = 'SQLite Error: '.$code.PHP_EOL;
    $message .= $error.PHP_EOL;
    $message .= 'Failed query:';
    $message .= (substr_count($query, PHP_EOL)===0) ? ' ' : PHP_EOL;
    $message .= $query;

    return $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the description of the error.
   *
   * @return string
   */
  public function getError(): string
  {
    return $this->error;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the error code of the error
   *
   * @return string
   */
  public function getErrorCode(): string
  {
    return $this->code;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getName(): string
  {
    return 'SQLite Error';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
