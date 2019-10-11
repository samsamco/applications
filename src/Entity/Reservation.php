<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    public function __construct()
    {
        $this->date_res = new \DateTime();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_res;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombre_part;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $rente;


    /***********etragers************/

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scpi", inversedBy="Scpi" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $Scpi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="User" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $User;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRes(): ?\DateTimeInterface
    {
        return $this->date_res;
    }

    public function setDateRes(?\DateTimeInterface $date_res): self
    {
        $this->date_res = $date_res;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): self
    {
        $this->montant = $montant;

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

    public function getRente(): ?string
    {
        return $this->rente;
    }

    public function setRente(?string $rente): self
    {
        $this->rente = $rente;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
