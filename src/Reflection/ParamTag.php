<?php
declare(strict_types=1);

namespace SetBased\Stratum\SqlitePdo\Reflection;

use Zend\Code\Reflection\DocBlock\Tag\PhpDocTypedTagInterface;
use Zend\Code\Reflection\DocBlock\Tag\TagInterface;

/**
 * Class ParamTag
 */
class ParamTag implements TagInterface, PhpDocTypedTagInterface
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string
   */
  protected $description;

  /**
   * @var array
   */
  protected $types = [];

  /**
   * @var string
   */
  protected $variableName;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the name of the tag.
   *
   * @return string
   */
  public function getName()
  {
    return 'param';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return all types supported by the tag definition
   *
   * @return string[]
   */
  public function getTypes()
  {
    return $this->types;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the parameter name.
   *
   * @return string
   */
  public function getVariableName()
  {
    return $this->variableName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Initializer
   *
   * @param string $tagDocBlockLine
   */
  public function initialize($tagDocBlockLine)
  {
    $matches = [];

    if (!preg_match('#((?:[\w|\\\]+(?:\[])*\|?)+)(?:\s+(:\S+))?(?:\s+(.*))?#s', $tagDocBlockLine, $matches))
    {
      return;
    }

    $this->types = explode('|', $matches[1]);

    if (isset($matches[2]))
    {
      $this->variableName = $matches[2];
    }

    if (isset($matches[3]))
    {
      $this->description = trim(preg_replace('#\s+#', ' ', $matches[3]));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

