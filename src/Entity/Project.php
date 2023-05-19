<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?User $user = null;

    #[ORM\Column(length: 128)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectMilestones::class, orphanRemoval: true)]
    private Collection $projectMilestones;

    public function __construct()
    {
        $this->projectMilestones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ProjectMilestones>
     */
    public function getProjectMilestones(): Collection
    {
        return $this->projectMilestones;
    }

    public function addProjectMilestone(ProjectMilestones $projectMilestone): self
    {
        if (!$this->projectMilestones->contains($projectMilestone)) {
            $this->projectMilestones->add($projectMilestone);
            $projectMilestone->setProject($this);
        }

        return $this;
    }

    public function removeProjectMilestone(ProjectMilestones $projectMilestone): self
    {
        if ($this->projectMilestones->removeElement($projectMilestone)) {
            if ($projectMilestone->getProject() === $this) {
                $projectMilestone->setProject(null);
            }
        }

        return $this;
    }
}
