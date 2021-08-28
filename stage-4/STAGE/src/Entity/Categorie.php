<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descrip;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="categorie")
     */
    private $prodcat;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $color;


    public function __construct()
    {
        $this->prodcat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescrip(): ?string
    {
        return $this->descrip;
    }

    public function setDescrip(string $descrip): self
    {
        $this->descrip = $descrip;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProdcat(): Collection
    {
        return $this->prodcat;
    }

    public function addProdcat(Produit $prodcat): self
    {
        if (!$this->prodcat->contains($prodcat)) {
            $this->prodcat[] = $prodcat;
            $prodcat->setCategorie($this);
        }

        return $this;
    }

    public function removeProdcat(Produit $prodcat): self
    {
        if ($this->prodcat->removeElement($prodcat)) {
            // set the owning side to null (unless already changed)
            if ($prodcat->getCategorie() === $this) {
                $prodcat->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->nom;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

}
