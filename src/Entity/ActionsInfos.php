<?php

namespace App\Entity;

use App\Repository\ActionsInfosRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use App\Entity\Personne;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[InheritanceType("JOINED")]

#[ORM\Entity(repositoryClass: ActionsInfosRepository::class)]
class ActionsInfos 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    protected $CreerPar;

    #[ORM\Column(type: 'date',nullable: true)]
    protected $CreerLe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected $ModifierPar;

    #[ORM\Column(type: 'date', nullable: true)]
    protected $ModifierLe;

    #[ORM\Column(type: 'boolean', nullable: true)]
    protected $Enable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreerPar(): ?string
    {
        return $this->CreerPar;
    }

    public function setCreerPar(string $CreerPar): self
    {
        $this->CreerPar = $CreerPar;

        return $this;
    }

    public function getCreerLe(): ?\DateTimeInterface
    {
        return $this->CreerLe;
    }

    public function setCreerLe(\DateTimeInterface $CreerLe): self
    {
        $this->CreerLe = $CreerLe;

        return $this;
    }

    public function getModifierPar(): ?string
    {
        return $this->ModifierPar;
    }

    public function setModifierPar(?string $ModifierPar): self
    {
        $this->ModifierPar = $ModifierPar;

        return $this;
    }

    public function getModifierLe(): ?\DateTimeInterface
    {
        return $this->ModifierLe;
    }

    public function setModifierLe(?\DateTimeInterface $ModifierLe): self
    {
        $this->ModifierLe = $ModifierLe;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->Enable;
    }

    public function setEnable(?bool $Enable): self
    {
        $this->Enable = $Enable;

        return $this;
    }
}
