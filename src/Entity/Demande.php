<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeRepository")
 */
class Demande
{
    public function __construct()
    {
        $this->date_demande = new \DateTime();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $type_demande;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_demande;



    /**************Etrangers********/
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="User" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scpi", inversedBy="Scpi" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $Scpi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombre_part;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDemande(): ?string
    {
        return $this->type_demande;
    }

    public function setTypeDemande(?string $type_demande): self
    {
        $this->type_demande = $type_demande;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(?\DateTimeInterface $date_demande): self
    {
        $this->date_demande = $date_demande;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getScpi(): ?Scpi
    {
        return $this->Scpi;
    }

    public function setScpi(?Scpi $Scpi): self
    {
        $this->Scpi = $Scpi;

        return $this;
    }

    public function getNombrePart(): ?int
    {
        return $this->nombre_part;
    }

    public function setNombrePart(?int $nombre_part): self
    {
        $this->nombre_part = $nombre_part;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
