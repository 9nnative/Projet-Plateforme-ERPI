<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $green;

    /**
     * @ORM\Column(type="boolean")
     */
    private $orange;

    /**
     * @ORM\Column(type="boolean")
     */
    private $red;

    /**
     * @ORM\Column(type="boolean")
     */
    private $none;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="state")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="state")
     */
    private $tasks;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGreen(): ?bool
    {
        return $this->green;
    }

    public function setGreen(bool $green): self
    {
        $this->green = $green;

        return $this;
    }

    public function getOrange(): ?bool
    {
        return $this->orange;
    }

    public function setOrange(bool $orange): self
    {
        $this->orange = $orange;

        return $this;
    }

    public function getRed(): ?bool
    {
        return $this->red;
    }

    public function setRed(bool $red): self
    {
        $this->red = $red;

        return $this;
    }

    public function getNone(): ?bool
    {
        return $this->none;
    }

    public function setNone(bool $none): self
    {
        $this->none = $none;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setState($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getState() === $this) {
                $project->setState(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setState($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getState() === $this) {
                $task->setState(null);
            }
        }

        return $this;
    }
    
}
