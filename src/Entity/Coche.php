<?php

namespace App\Entity;

use App\Repository\CocheRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity(repositoryClass=CocheRepository::class)
 * @ExclusionPolicy("all")
 */
class Coche
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
    private $imatge;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $preu;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="categoria")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
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

    public function getImatge(): ?string
    {
        return $this->imatge;
    }

    public function setImatge(string $imatge): self
    {
        $this->imatge = $imatge;

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

    public function getPreu(): ?int
    {
        return $this->preu;
    }

    public function setPreu(int $preu): self
    {
        $this->preu = $preu;

        return $this;
    }

    public function getCategoria(): ?categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
}
