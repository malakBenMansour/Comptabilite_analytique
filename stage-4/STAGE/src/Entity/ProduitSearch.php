<?php
namespace App\Entity;

class ProduitSearch
{

    /**
     * @var int|null
     */
private $maxPrice;
    /**
     * @var int|null
     */
private $minQuantite;

    /**
     * @return int|null
     */

    public function getMinQuantite(): ?int
    {
        return $this->minQuantite;
    }

    /**
     * @param int|null $minQuantite
     */
    public function setMinQuantite(int $minQuantite): void
    {
        $this->minQuantite = $minQuantite;
    }

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     */
    public function setMaxPrice(int $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }


}