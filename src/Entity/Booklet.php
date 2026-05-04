<?php

namespace App\Entity;

use App\Repository\BookletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookletRepository::class)]
class Booklet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $storage = null;

    #[ORM\ManyToOne(inversedBy: 'booklets')]
    private ?Formation $formation = null;

    #[ORM\ManyToOne(inversedBy: 'booklets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Skill $skill = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $weekContent = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $validatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $weekNumber = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $weekStart = null;

    /**
     * @var Collection<int, Ecf>
     */
    #[ORM\OneToMany(targetEntity: Ecf::class, mappedBy: 'booklet')]
    private Collection $ecfs;

    #[ORM\OneToOne(mappedBy: 'booklet', cascade: ['persist', 'remove'])]
    private ?Bilan $bilan = null;

    /**
     * @var Collection<int, SkillAssessment>
     */
    #[ORM\OneToMany(targetEntity: SkillAssessment::class, mappedBy: 'booklet', orphanRemoval: true)]
    private Collection $skillAssessments;

    /**
     * @var Collection<int, CompanyProgress>
     */
    #[ORM\OneToMany(targetEntity: CompanyProgress::class, mappedBy: 'booklet', orphanRemoval: true)]
    private Collection $companyProgress;

    /**
     * @var Collection<int, CompanyVisit>
     */
    #[ORM\OneToMany(targetEntity: CompanyVisit::class, mappedBy: 'booklet', orphanRemoval: true)]
    private Collection $companyVisits;

    /**
     * @var Collection<int, BehaviorAssessment>
     */
    #[ORM\OneToMany(targetEntity: BehaviorAssessment::class, mappedBy: 'booklet', orphanRemoval: true)]
    private Collection $behaviorAssessments;

    public function __construct()
    {
        $this->ecfs = new ArrayCollection();
        $this->skillAssessments = new ArrayCollection();
        $this->companyProgress = new ArrayCollection();
        $this->companyVisits = new ArrayCollection();
        $this->behaviorAssessments = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function isStorage(): ?bool { return $this->storage; }
    public function setStorage(?bool $storage): static { $this->storage = $storage; return $this; }

    public function getFormation(): ?Formation { return $this->formation; }
    public function setFormation(?Formation $formation): static { $this->formation = $formation; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    public function getSkill(): ?Skill { return $this->skill; }
    public function setSkill(?Skill $skill): static { $this->skill = $skill; return $this; }

    public function getWeekContent(): ?string { return $this->weekContent; }
    public function setWeekContent(?string $weekContent): static { $this->weekContent = $weekContent; return $this; }

    public function isValidated(): ?bool { return $this->validated; }
    public function setValidated(?bool $validated): static { $this->validated = $validated; return $this; }

    public function getValidatedAt(): ?\DateTime { return $this->validatedAt; }
    public function setValidatedAt(?\DateTime $validatedAt): static { $this->validatedAt = $validatedAt; return $this; }

    public function getWeekNumber(): ?int { return $this->weekNumber; }
    public function setWeekNumber(?int $weekNumber): static { $this->weekNumber = $weekNumber; return $this; }

    public function getWeekStart(): ?\DateTime { return $this->weekStart; }
    public function setWeekStart(?\DateTime $weekStart): static { $this->weekStart = $weekStart; return $this; }

    /**
     * @return Collection<int, Ecf>
     */
    public function getEcfs(): Collection
    {
        return $this->ecfs;
    }

    public function addEcf(Ecf $ecf): static
    {
        if (!$this->ecfs->contains($ecf)) {
            $this->ecfs->add($ecf);
            $ecf->setBooklet($this);
        }

        return $this;
    }

    public function removeEcf(Ecf $ecf): static
    {
        if ($this->ecfs->removeElement($ecf)) {
            // set the owning side to null (unless already changed)
            if ($ecf->getBooklet() === $this) {
                $ecf->setBooklet(null);
            }
        }

        return $this;
    }

    public function getBilan(): ?Bilan
    {
        return $this->bilan;
    }

    public function setBilan(Bilan $bilan): static
    {
        // set the owning side of the relation if necessary
        if ($bilan->getBooklet() !== $this) {
            $bilan->setBooklet($this);
        }

        $this->bilan = $bilan;

        return $this;
    }

    /**
     * @return Collection<int, SkillAssessment>
     */
    public function getSkillAssessments(): Collection
    {
        return $this->skillAssessments;
    }

    public function addSkillAssessment(SkillAssessment $skillAssessment): static
    {
        if (!$this->skillAssessments->contains($skillAssessment)) {
            $this->skillAssessments->add($skillAssessment);
            $skillAssessment->setBooklet($this);
        }

        return $this;
    }

    public function removeSkillAssessment(SkillAssessment $skillAssessment): static
    {
        if ($this->skillAssessments->removeElement($skillAssessment)) {
            // set the owning side to null (unless already changed)
            if ($skillAssessment->getBooklet() === $this) {
                $skillAssessment->setBooklet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CompanyProgress>
     */
    public function getCompanyProgress(): Collection
    {
        return $this->companyProgress;
    }

    public function addCompanyProgress(CompanyProgress $companyProgress): static
    {
        if (!$this->companyProgress->contains($companyProgress)) {
            $this->companyProgress->add($companyProgress);
            $companyProgress->setBooklet($this);
        }

        return $this;
    }

    public function removeCompanyProgress(CompanyProgress $companyProgress): static
    {
        if ($this->companyProgress->removeElement($companyProgress)) {
            // set the owning side to null (unless already changed)
            if ($companyProgress->getBooklet() === $this) {
                $companyProgress->setBooklet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CompanyVisit>
     */
    public function getCompanyVisits(): Collection
    {
        return $this->companyVisits;
    }

    public function addCompanyVisit(CompanyVisit $companyVisit): static
    {
        if (!$this->companyVisits->contains($companyVisit)) {
            $this->companyVisits->add($companyVisit);
            $companyVisit->setBooklet($this);
        }

        return $this;
    }

    public function removeCompanyVisit(CompanyVisit $companyVisit): static
    {
        if ($this->companyVisits->removeElement($companyVisit)) {
            // set the owning side to null (unless already changed)
            if ($companyVisit->getBooklet() === $this) {
                $companyVisit->setBooklet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BehaviorAssessment>
     */
    public function getBehaviorAssessments(): Collection
    {
        return $this->behaviorAssessments;
    }

    public function addBehaviorAssessment(BehaviorAssessment $behaviorAssessment): static
    {
        if (!$this->behaviorAssessments->contains($behaviorAssessment)) {
            $this->behaviorAssessments->add($behaviorAssessment);
            $behaviorAssessment->setBooklet($this);
        }

        return $this;
    }

    public function removeBehaviorAssessment(BehaviorAssessment $behaviorAssessment): static
    {
        if ($this->behaviorAssessments->removeElement($behaviorAssessment)) {
            // set the owning side to null (unless already changed)
            if ($behaviorAssessment->getBooklet() === $this) {
                $behaviorAssessment->setBooklet(null);
            }
        }

        return $this;
    }
}