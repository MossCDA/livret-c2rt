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

    #[ORM\Column]
    private ?\DateTime $beginAt = null;

    #[ORM\Column]
    private ?\DateTime $endAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $beginStageAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endStageAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $storage = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeCenter = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeStage = null;

    #[ORM\ManyToOne(inversedBy: 'formateur')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeFormation $typeFormation = null;

    /**
     * @var Collection<int, Booklet>
     */
    #[ORM\OneToMany(targetEntity: Booklet::class, mappedBy: 'formation')]
    private Collection $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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

    public function getBeginAt(): ?\DateTime
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTime $beginAt): static
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getBeginStageAt(): ?\DateTime
    {
        return $this->beginStageAt;
    }

    public function setBeginStageAt(?\DateTime $beginStageAt): static
    {
        $this->beginStageAt = $beginStageAt;

        return $this;
    }

    public function getEndStageAt(): ?\DateTime
    {
        return $this->endStageAt;
    }

    public function setEndStageAt(?\DateTime $endStageAt): static
    {
        $this->endStageAt = $endStageAt;

        return $this;
    }

    public function isStorage(): ?bool
    {
        return $this->storage;
    }

    public function setStorage(?bool $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getTimeCenter(): ?int
    {
        return $this->timeCenter;
    }

    public function setTimeCenter(?int $timeCenter): static
    {
        $this->timeCenter = $timeCenter;

        return $this;
    }

    public function getTimeStage(): ?int
    {
        return $this->timeStage;
    }

    public function setTimeStage(?int $timeStage): static
    {
        $this->timeStage = $timeStage;

        return $this;
    }

    public function getTypeFormation(): ?TypeFormation
    {
        return $this->typeFormation;
    }

    public function setTypeFormation(?TypeFormation $typeFormation): static
    {
        $this->typeFormation = $typeFormation;

        return $this;
    }

    /**
     * @return Collection<int, Booklet>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Booklet $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setFormation($this);
        }

        return $this;
    }

    public function removeUser(Booklet $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getFormation() === $this) {
                $user->setFormation(null);
            }
        }

        return $this;
    }
}
