<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Membre::class)]
    private Collection $membre;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Vehicule::class)]
    private Collection $vehicule;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_heur_depart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_heur_fin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_enregistrement = null;

    public function __construct()
    {
        $this->membre = new ArrayCollection();
        $this->vehicule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Membre>
     */
    public function getMembre(): Collection
    {
        return $this->membre;
    }

    public function addMembre(Membre $membre): static
    {
        if (!$this->membre->contains($membre)) {
            $this->membre->add($membre);
            $membre->setCommande($this);
        }

        return $this;
    }

    public function removeMembre(Membre $membre): static
    {
        if ($this->membre->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getCommande() === $this) {
                $membre->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getVehicule(): Collection
    {
        return $this->vehicule;
    }

    public function addVehicule(Vehicule $vehicule): static
    {
        if (!$this->vehicule->contains($vehicule)) {
            $this->vehicule->add($vehicule);
            $vehicule->setCommande($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): static
    {
        if ($this->vehicule->removeElement($vehicule)) {
            // set the owning side to null (unless already changed)
            if ($vehicule->getCommande() === $this) {
                $vehicule->setCommande(null);
            }
        }

        return $this;
    }

    public function getDateHeurDepart(): ?\DateTimeInterface
    {
        return $this->date_heur_depart;
    }

    public function setDateHeurDepart(\DateTimeInterface $date_heur_depart): static
    {
        $this->date_heur_depart = $date_heur_depart;

        return $this;
    }

    public function getDateHeurFin(): ?\DateTimeInterface
    {
        return $this->date_heur_fin;
    }

    public function setDateHeurFin(\DateTimeInterface $date_heur_fin): static
    {
        $this->date_heur_fin = $date_heur_fin;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): static
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
    }
}
