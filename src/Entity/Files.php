<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilesRepository::class)
 */
class Files
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unique_name;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="files")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="files")
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $summary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getUniqueName(): ?string
    {
        return $this->unique_name;
    }

    public function setUniqueName(string $unique_name): self
    {
        $this->unique_name = $unique_name;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getBrochureFilename()
    {
        return $this->unique_name;
    }

    public function setBrochureFilename($unique_name)
    {
        $this->unique_name = $unique_name;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }
}
