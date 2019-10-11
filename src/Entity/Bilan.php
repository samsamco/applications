<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BilanRepository")
 */
class Bilan
{
    public function __construct()
    {
        $this->date_bilan = new \DateTime();
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
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_historique;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_bilan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlHistorique(): ?string
    {
        return $this->url_historique;
    }

    public function setUrlHistorique(?string $url_historique): self
    {
        $this->url_historique = $url_historique;

        return $this;
    }

    public function getDateBilan(): ?\DateTimeInterface
    {
        return $this->date_bilan;
    }

    public function setDateBilan(?\DateTimeInterface $date_bilan): self
    {
        $this->date_bilan = $date_bilan;

        return $this;
    }
}
