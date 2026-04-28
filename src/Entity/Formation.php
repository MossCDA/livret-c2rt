<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $beginAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $beginStageAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $endStageAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $storage = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeCenter = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeStage = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeFormation $typeFormation = null;

    #[ORM\ManyToOne(inversedBy: 'formationsAsFormateur')]
    private ?User $formateur = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Booklet::class)]
    private Collection $booklets;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->booklets = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getBeginAt(): ?\DateTimeInterface { return $this->beginAt; }
    public function setBeginAt(\DateTimeInterface $beginAt): static { $this->beginAt = $beginAt; return $this; }

    public function getEndAt(): ?\DateTimeInterface { return $this->endAt; }
    public function setEndAt(\DateTimeInterface $endAt): static { $this->endAt = $endAt; return $this; }

    public function getBeginStageAt(): ?\DateTimeInterface { return $this->beginStageAt; }
    public function setBeginStageAt(?\DateTimeInterface $beginStageAt): static { $this->beginStageAt = $beginStageAt; return $this; }

    public function getEndStageAt(): ?\DateTimeInterface { return $this->endStageAt; }
    public function setEndStageAt(?\DateTimeInterface $endStageAt): static { $this->endStageAt = $endStageAt; return $this; }

    public function getStorage(): ?bool { return $this->storage; }
    public function setStorage(?bool $storage): static { $this->storage = $storage; return $this; }

    public function getTimeCenter(): ?int { return $this->timeCenter; }
    public function setTimeCenter(?int $timeCenter): static { $this->timeCenter = $timeCenter; return $this; }

    public function getTimeStage(): ?int { return $this->timeStage; }
    public function setTimeStage(?int $timeStage): static { $this->timeStage = $timeStage; return $this; }

    public function getTypeFormation(): ?TypeFormation { return $this->typeFormation; }
    public function setTypeFormation(?TypeFormation $typeFormation): static { $this->typeFormation = $typeFormation; return $this; }

    public function getFormateur(): ?User { return $this->formateur; }
    public function setFormateur(?User $formateur): static { $this->formateur = $formateur; return $this; }

    public function getBooklets(): Collection { return $this->booklets; }
    public function getUsers(): Collection { return $this->users; }
}