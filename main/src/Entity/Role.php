<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
#[InheritanceType("JOINED")]

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role extends ActionsInfos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $NomRole;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRole(): ?string
    {
        return $this->NomRole;
    }

    public function setNomRole(string $NomRole): self
    {
        $this->NomRole = $NomRole;

        return $this;
    }
}
