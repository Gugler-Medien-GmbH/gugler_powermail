<?php
namespace Gugler\GuglerPowermail\Domain\Model;

/**
 * Class Field
 * @package Gugler\GuglerPowermail\Domain\Model
 */
class Field extends \In2code\Powermail\Domain\Model\Field
{
    protected string $autocomplete;
    protected string $itemsPerRow;
    protected string $justifyContent;

    /**
     * @param string $autocomplete
     * @return void
     */
    public function setAutocomplete(string $autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    /**
     * @return string
     */
    public function getAutocomplete():string
    {
        return $this->autocomplete;
    }


    public function getItemsPerRow():string
    {
        return $this->itemsPerRow;
    }

    public function setItemsPerRow(string $itemsPerRow): void
    {
        $this->itemsPerRow = $itemsPerRow;
    }

    public function getJustifyContent(): string
    {
        return $this->justifyContent;
    }

    public function setJustifyContent(string $justifyContent): void
    {
        $this->justifyContent = $justifyContent;
    }

}
