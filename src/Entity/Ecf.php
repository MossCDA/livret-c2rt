<?php

namespace App\Entity;

use App\Repository\EcfRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcfRepository::class)]
class Ecf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $grade = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $evaluatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'ecfs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?booklet $booklet = null;

    #[ORM\ManyToOne(inversedBy: 'ecfs')]
    private ?Skill $skill = null;

    #[ORM\Column(length: 255)]
    private ?string $no = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getEvaluatedAt(): ?\DateTime
    {
        return $this->evaluatedAt;
    }

    public function setEvaluatedAt(?\DateTime $evaluatedAt): static
    {
        $this->evaluatedAt = $evaluatedAt;

        return $this;
    }

    public function getBooklet(): ?booklet
    {
        return $this->booklet;
    }

    public function setBooklet(?booklet $booklet): static
    {
        $this->booklet = $booklet;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): static
    {
        $this->skill = $skill;

        return $this;
    }

    public function getNo(): ?string
    {
        return $this->no;
    }

    public function setNo(string $no): static
    {
        $this->no = $no;

        return $this;
    }
}
