<?php

namespace App\Entity;

use App\Repository\TypeFormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeFormationRepository::class)]
class TypeFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $code = null;

    /**
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'typeFormation')]
    private Collection $formateur;

    /**
     * @var Collection<int, ActiviteType>
     */
    #[ORM\OneToMany(targetEntity: ActiviteType::class, mappedBy: 'typeFormation')]
    private Collection $activiteTypes;

    public function __construct()
    {
        $this->formateur = new ArrayCollection();
        $this->activiteTypes = new ArrayCollection();
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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formation $formateur): static
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur->add($formateur);
            $formateur->setTypeFormation($this);
        }

        return $this;
    }

    public function removeFormateur(Formation $formateur): static
    {
        if ($this->formateur->removeElement($formateur)) {
            // set the owning side to null (unless already changed)
            if ($formateur->getTypeFormation() === $this) {
                $formateur->setTypeFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActiviteType>
     */
    public function getActiviteTypes(): Collection
    {
        return $this->activiteTypes;
    }

    public function addActiviteType(ActiviteType $activiteType): static
    {
        if (!$this->activiteTypes->contains($activiteType)) {
            $this->activiteTypes->add($activiteType);
            $activiteType->setTypeFormation($this);
        }

        return $this;
    }

    public function removeActiviteType(ActiviteType $activiteType): static
    {
        if ($this->activiteTypes->removeElement($activiteType)) {
            // set the owning side to null (unless already changed)
            if ($activiteType->getTypeFormation() === $this) {
                $activiteType->setTypeFormation(null);
            }
        }

        return $this;
    }
}
