<?php

namespace App\Entity;

use App\Repository\CompanyVisitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyVisitRepository::class)]
class CompanyVisit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $visitDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $trainerName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $studentComments = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tutorComments = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $trainerComments = null;

    #[ORM\ManyToOne(inversedBy: 'companyVisits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Booklet $booklet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitDate(): ?\DateTime
    {
        return $this->visitDate;
    }

    public function setVisitDate(?\DateTime $visitDate): static
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    public function getTrainerName(): ?string
    {
        return $this->trainerName;
    }

    public function setTrainerName(?string $trainerName): static
    {
        $this->trainerName = $trainerName;

        return $this;
    }

    public function getStudentComments(): ?string
    {
        return $this->studentComments;
    }

    public function setStudentComments(?string $studentComments): static
    {
        $this->studentComments = $studentComments;

        return $this;
    }

    public function getTutorComments(): ?string
    {
        return $this->tutorComments;
    }

    public function setTutorComments(?string $tutorComments): static
    {
        $this->tutorComments = $tutorComments;

        return $this;
    }

    public function getTrainerComments(): ?string
    {
        return $this->trainerComments;
    }

    public function setTrainerComments(?string $trainerComments): static
    {
        $this->trainerComments = $trainerComments;

        return $this;
    }

    public function getBooklet(): ?Booklet
    {
        return $this->booklet;
    }

    public function setBooklet(?Booklet $booklet): static
    {
        $this->booklet = $booklet;

        return $this;
    }
}
