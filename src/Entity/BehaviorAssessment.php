<?php

namespace App\Entity;

use App\Repository\BehaviorAssessmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BehaviorAssessmentRepository::class)]
class BehaviorAssessment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $period = null;

    #[ORM\Column(length: 10)]
    private ?string $assessedBy = null;

    #[ORM\Column(length: 255)]
    private ?string $criteria = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $rating = null;

    #[ORM\ManyToOne(inversedBy: 'behaviorAssessments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Booklet $booklet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getAssessedBy(): ?string
    {
        return $this->assessedBy;
    }

    public function setAssessedBy(string $assessedBy): static
    {
        $this->assessedBy = $assessedBy;

        return $this;
    }

    public function getCriteria(): ?string
    {
        return $this->criteria;
    }

    public function setCriteria(string $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): static
    {
        $this->rating = $rating;

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
