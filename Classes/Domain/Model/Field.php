<?php
namespace Gugler\GuglerPowermail\Domain\Model;

/**
 * Class Field
 * @package Gugler\GuglerPowermail\Domain\Model
 */
class Field extends \In2code\Powermail\Domain\Model\Field
{
  /**
   * New property autocomplete
   *
   * @var string $autocomplete
   */
  protected $autocomplete;

  /**
   * @param string $autocomplete
   * @return void
   */
  public function setAutocomplete($autocomplete)
  {
    $this->autocomplete = $autocomplete;
  }

  /**
   * @return string
   */
  public function getAutocomplete()
  {
    return $this->autocomplete;
  }
}
