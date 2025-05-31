<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture extends ActionsInfos
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $numserie;

    #[ORM\Column(type: 'string', length: 255)]
    private $NumId;

    #[ORM\Column(type: 'date',nullable: true)]
    private $DateAchat;

    #[ORM\Column(type: 'string', length: 255)]
    private $couleur;

    #[ORM\Column(type: 'integer')]
    private $prix;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\OneToOne(mappedBy: 'voiture', targetEntity: Vente::class, cascade: ['persist', 'remove'])]
    private $vente;

    #[ORM\Column(type: 'string', length: 255)]
    private $statut;

    #[ORM\Column(type: 'date')]
    private $DatePubli;

    #[ORM\OneToMany(mappedBy: 'Voiture', targetEntity: Reservation::class)]
    private $reservations;

    #[ORM\ManyToOne(targetEntity: Marque::class, inversedBy: 'voiture')]
    #[ORM\JoinColumn(nullable: false)]
    private $marque;

    #[ORM\ManyToOne(targetEntity: Modele::class, inversedBy: 'voiture')]
    #[ORM\JoinColumn(nullable: false)]
    private $modele;

    #[ORM\Column(type: 'string', length: 255)]
    private $Puissance;

    #[ORM\Column(type: 'string', length: 255)]
    private $Kilometrage;

    #[ORM\Column(type: 'date')]
    private $DateMiseCircu;

    #[ORM\Column(type: 'string', length: 255)]
    private $Etat;

    #[ORM\Column(type: 'string', length: 255)]
    private $Siege;

    #[ORM\Column(type: 'string', length: 255)]
    private $Interieur;

    #[ORM\Column(type: 'string', length: 255)]
    private $Motorisation;

    #[ORM\Column(type: 'date')]
    private $Annee;


    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNumserie(): ?string
    {
        return $this->numserie;
    }

    public function setNumserie(string $numserie): self
    {
        $this->numserie = $numserie;

        return $this;
    }

    public function getNumId(): ?string
    {
        return $this->NumId;
    }

    public function setNumId(string $NumId): self
    {
        $this->NumId = $NumId;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->DateAchat;
    }

    public function setDateAchat(\DateTimeInterface $DateAchat): self
    {
        $this->DateAchat = $DateAchat;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    
    public function setImage(?string $image = null): void
    {
        $this->image = $image;
    
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    
    public function setStatut(?string $statut = null): void
    {
        $this->statut = $statut;
    
    }


    public function getVente(): ?Vente
    {
        return $this->vente;
    }
    

    public function __toString() 
    {
        return (string) $this->modele; 
    }

    
    public function setVente(Vente $vente): self
    {
        // set the owning side of the relation if necessary
        if ($vente->getVoiture() !== $this) {
            $vente->setVoiture($this);
        }

        $this->vente = $vente;

        return $this;
    }


    public function getDatePubli(): ?\DateTimeInterface
    {
        return $this->DatePubli;
    }

    public function setDatePubli(\DateTimeInterface $DatePubli): self
    {
        $this->DatePubli = $DatePubli;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setVoiture($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVoiture() === $this) {
                $reservation->setVoiture(null);
            }
        }

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getPuissance(): ?string
    {
        return $this->Puissance;
    }

    public function setPuissance(string $Puissance): self
    {
        $this->Puissance = $Puissance;

        return $this;
    }


    public function getKilometrage(): ?string
    {
        return $this->Kilometrage;
    }

    public function setKilometrage(string $Kilometrage): self
    {
        $this->Kilometrage = $Kilometrage;

        return $this;
    }

    public function getDateMiseCircu(): ?\DateTimeInterface
    {
        return $this->DateMiseCircu;
    }

    public function setDateMiseCircu(\DateTimeInterface $DateMiseCircu): self
    {
        $this->DateMiseCircu = $DateMiseCircu;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getSiege(): ?string
    {
        return $this->Siege;
    }

    public function setSiege(string $Siege): self
    {
        $this->Siege = $Siege;

        return $this;
    }

    public function getInterieur(): ?string
    {
        return $this->Interieur;
    }

    public function setInterieur(string $Interieur): self
    {
        $this->Interieur = $Interieur;

        return $this;
    }

    public function getMotorisation(): ?string
    {
        return $this->Motorisation;
    }

    public function setMotorisation(string $Motorisation): self
    {
        $this->Motorisation = $Motorisation;

        return $this;
    }

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->Annee;
    }

    public function setAnnee(\DateTimeInterface $Annee): self
    {
        $this->Annee = $Annee;

        return $this;
    }

}
