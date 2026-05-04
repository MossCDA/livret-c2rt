<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Booklet>
     */
    #[ORM\OneToMany(targetEntity: Booklet::class, mappedBy: 'skill')]
    private Collection $slot;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActiviteType $activiteType = null;

    /**
     * @var Collection<int, Ecf>
     */
    #[ORM\OneToMany(targetEntity: Ecf::class, mappedBy: 'skill')]
    private Collection $ecfs;

    /**
     * @var Collection<int, SkillAssessment>
     */
    #[ORM\OneToMany(targetEntity: SkillAssessment::class, mappedBy: 'skill', orphanRemoval: true)]
    private Collection $skillAssessments;

    public function __construct()
    {
        $this->slot = new ArrayCollection();
        $this->ecfs = new ArrayCollection();
        $this->skillAssessments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Booklet>
     */
    public function getSlot(): Collection
    {
        return $this->slot;
    }

    public function addSlot(Booklet $slot): static
    {
        if (!$this->slot->contains($slot)) {
            $this->slot->add($slot);
            $slot->setSkill($this);
        }

        return $this;
    }

    public function removeSlot(Booklet $slot): static
    {
        if ($this->slot->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getSkill() === $this) {
                $slot->setSkill(null);
            }
        }

        return $this;
    }

    public function getActiviteType(): ?ActiviteType
    {
        return $this->activiteType;
    }

    public function setActiviteType(?ActiviteType $activiteType): static
    {
        $this->activiteType = $activiteType;

        return $this;
    }

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
            $ecf->setSkill($this);
        }

        return $this;
    }

    public function removeEcf(Ecf $ecf): static
    {
        if ($this->ecfs->removeElement($ecf)) {
            // set the owning side to null (unless already changed)
            if ($ecf->getSkill() === $this) {
                $ecf->setSkill(null);
            }
        }

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
            $skillAssessment->setSkill($this);
        }

        return $this;
    }

    public function removeSkillAssessment(SkillAssessment $skillAssessment): static
    {
        if ($this->skillAssessments->removeElement($skillAssessment)) {
            // set the owning side to null (unless already changed)
            if ($skillAssessment->getSkill() === $this) {
                $skillAssessment->setSkill(null);
            }
        }

        return $this;
    }
}
