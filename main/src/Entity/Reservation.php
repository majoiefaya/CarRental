<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation extends ActionsInfos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\ManyToOne(targetEntity: Voiture::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private $Voiture;

    #[ORM\Column(type: 'float')]
    private $PrixReservation;

    #[ORM\Column(type: 'date')]
    private $DateReservation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVoiture(): ?Voiture
    {
        return $this->Voiture;
    }

    public function setVoiture(?Voiture $Voiture): self
    {
        $this->Voiture = $Voiture;

        return $this;
    }

    public function getPrixReservation(): ?float
    {
        return $this->PrixReservation;
    }

    public function setPrixReservation(float $PrixReservation): self
    {
        $this->PrixReservation = $PrixReservation;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->DateReservation;
    }

    public function setDateReservation(\DateTimeInterface $DateReservation): self
    {
        $this->DateReservation = $DateReservation;

        return $this;
    }
}
