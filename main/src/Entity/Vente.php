<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente extends ActionsInfos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'integer')]
    private $montant;

    #[ORM\Column(type: 'date')]
    private $DateVente;

    #[ORM\OneToOne(inversedBy: 'vente', targetEntity: Voiture::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private $voiture;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'ventes')]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->DateVente;
    }

    public function setDateVente(\DateTimeInterface $DateVente): self
    {
        $this->DateVente = $DateVente;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
