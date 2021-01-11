<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sent_by;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="content")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sent_to;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sent_at;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     */
    private $conversation;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="last_message")
     */
    private $conversations;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSentBy(): ?User
    {
        return $this->sent_by;
    }

    public function setSentBy(?User $sent_by): self
    {
        $this->sent_by = $sent_by;

        return $this;
    }

    public function getSentTo(): ?User
    {
        return $this->sent_to;
    }

    public function setSentTo(?User $sent_to): self
    {
        $this->sent_to = $sent_to;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sent_at;
    }

    public function setSentAt(\DateTimeInterface $sent_at): self
    {
        $this->sent_at = $sent_at;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setLastMessage($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getLastMessage() === $this) {
                $conversation->setLastMessage(null);
            }
        }

        return $this;
    }
}
