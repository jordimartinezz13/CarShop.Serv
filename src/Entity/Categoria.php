<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
//gracias a esto hago que no sea una consulta en cascada
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 * @ExclusionPolicy("all")
 */
class Categoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Coche::class, mappedBy="categoria")
     */
    private $categoria;

    public function __construct()
    {
        $this->categoria = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    /**
     * @return Collection|Coche[]
     */
    public function getCategoria(): Collection
    {
        return $this->categoria;
    }

    public function addCategorium(Coche $categorium): self
    {
        if (!$this->categoria->contains($categorium)) {
            $this->categoria[] = $categorium;
            $categorium->setCategoria($this);
        }

        return $this;
    }

    public function removeCategorium(Coche $categorium): self
    {
        if ($this->categoria->removeElement($categorium)) {
            // set the owning side to null (unless already changed)
            if ($categorium->getCategoria() === $this) {
                $categorium->setCategoria(null);
            }
        }

        return $this;
    }
}
