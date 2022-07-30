<?php

namespace App\Entity;

use App\Repository\ResponsableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsableRepository::class)]
class Responsable extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

        
    #[ORM\Column(type: 'string', length: 255)]
    private $image;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    
    public function setImage(?string $image = null): void
    {
        $this->image = $image;
    
    }

    public function __toString() 
    {
        return (string) $this->nom; 
    }



}
