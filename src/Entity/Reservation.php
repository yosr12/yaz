<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Datedebut;

    /**
     * @ORM\Column(type="date")
     */
    private $Datefin;

    /**
     * @ORM\Column(type="integer")
     */
    private $Prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="question is required")
     */
    private $Methodedepaiement;

    /**
     * @ORM\ManyToOne(targetEntity=Voyage::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Voyage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->Datedebut;
    }

    public function setDatedebut(\DateTimeInterface $Datedebut): self
    {
        $this->Datedebut = $Datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->Datefin;
    }

    public function setDatefin(\DateTimeInterface $Datefin): self
    {
        $this->Datefin = $Datefin;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getMethodedepaiement(): ?string
    {
        return $this->Methodedepaiement;
    }

    public function setMethodedepaiement(string $Methodedepaiement): self
    {
        $this->Methodedepaiement = $Methodedepaiement;

        return $this;
    }

    public function getVoyage(): ?voyage
    {
        return $this->Voyage;
    }

    public function setVoyage(?voyage $Voyage): self
    {
        $this->Voyage = $Voyage;

        return $this;
    }
}
