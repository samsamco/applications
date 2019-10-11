<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{
    public function __construct()
    {
        $this->date_notification = new \DateTime();
        $this->statut = 0;
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
    private $date_notification;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    /*************Etrangers************/

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Demande", inversedBy="Demande" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $Demande;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="User" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $User;

    private $type_notification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateNotification(): ?\DateTimeInterface
    {
        return $this->date_notification;
    }

    public function setDateNotification(?\DateTimeInterface $date_notification): self
    {
        $this->date_notification = $date_notification;

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

    public function getTypeNotification(): ?string
    {
        return $this->type_notification;
    }

    public function setTypeNotification(?string $type_notification): self
    {
        $this->type_notification = $type_notification;

        return $this;
    }

    public function getDemande(): ?Demande
    {
        return $this->Demande;
    }

    public function setDemande(?Demande $Demande): self
    {
        $this->Demande = $Demande;

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
