<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScpiRepository")
 */
class Scpi
{
    public function __construct()
    {
        $this->is_valide = 0;
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nature;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $rendementactuel;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $valeur_part;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coleur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $is_valide;


    /**************Etrangers*********/

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gestionnaire", inversedBy="Gestionnaire")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $MyGestionnaire;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bilan", inversedBy="Bilan" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $Bilan;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="User" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $User;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(?string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getRendementactuel(): ?string
    {
        return $this->rendementactuel;
    }

    public function setRendementactuel(?string $rendementactuel): self
    {
        $this->rendementactuel = $rendementactuel;

        return $this;
    }

    public function getValeurPart(): ?string
    {
        return $this->valeur_part;
    }

    public function setValeurPart(?string $valeur_part): self
    {
        $this->valeur_part = $valeur_part;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getColeur(): ?string
    {
        return $this->coleur;
    }

    public function setColeur(?string $coleur): self
    {
        $this->coleur = $coleur;

        return $this;
    }

    public function getIsValide(): ?int
    {
        return $this->is_valide;
    }

    public function setIsValide(?int $is_valide): self
    {
        $this->is_valide = $is_valide;

        return $this;
    }

    public function getMyGestionnaire(): ?Gestionnaire
    {
        return $this->MyGestionnaire;
    }

    public function setMyGestionnaire(?Gestionnaire $MyGestionnaire): self
    {
        $this->MyGestionnaire = $MyGestionnaire;

        return $this;
    }

    public function getBilan(): ?Bilan
    {
        return $this->Bilan;
    }

    public function setBilan(?Bilan $Bilan): self
    {
        $this->Bilan = $Bilan;

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
