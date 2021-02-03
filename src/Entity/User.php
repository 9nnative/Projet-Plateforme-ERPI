<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="Il y a déjà un compte avec cette adresse-mail.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     */
    private $bio;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="owner")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="owner")
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, inversedBy="users", cascade={"persist", "remove"})
     */
    private $follow;

    /**
     * @ORM\ManyToMany(targetEntity=Task::class, mappedBy="user")
     */
    private $attrtask;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilepicpath;

    /**
     * @ORM\ManyToOne(targetEntity=Profilepics::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profilepics;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sent_by")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sent_to")
     */
    private $sent_message;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="owner_id")
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="recipient")
     */
    private $conversationsbis;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="sent_by")
     */
    private $tickets;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isadmin;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="users")
     */
    private $friend;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="friend")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="usertarget", orphanRemoval=true)
     */
    private $notifications;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->follow = new ArrayCollection();
        $this->attrtask = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->content = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->conversationsbis = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->friend = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getForename(): ?string
    {
        return $this->forename;
    }

    public function setForename(string $forename): self
    {
        $this->forename = $forename;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(?string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

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
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getOwner() === $this) {
                $project->setOwner(null);
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
            $task->setOwner($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getOwner() === $this) {
                $task->setOwner(null);
            }
        }

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getFollow(): Collection
    {
        return $this->follow;
    }

    public function addFollow(Project $follow): self
    {
        if (!$this->follow->contains($follow)) {
            $this->follow[] = $follow;
        }

        return $this;
    }

    public function removeFollow(Project $follow): self
    {
        $this->follow->removeElement($follow);

        return $this;
    }
    public function FindByUser()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isScientist = :isScientist')
            ->setParameter('isScientist', true);
    }

    /**
     * @return Collection|Task[]
     */
    public function getAttrtask(): Collection
    {
        return $this->attrtask;
    }

    public function addAttrtask(Task $attrtask): self
    {
        if (!$this->attrtask->contains($attrtask)) {
            $this->attrtask[] = $attrtask;
            $attrtask->addUser($this);
        }

        return $this;
    }

    public function removeAttrtask(Task $attrtask): self
    {
        if ($this->attrtask->removeElement($attrtask)) {
            $attrtask->removeUser($this);
        }

        return $this;
    }

    public function getProfilepicpath(): ?string
    {
        return $this->profilepicpath;
    }

    public function setProfilepicpath(?string $profilepicpath): self
    {
        $this->profilepicpath = $profilepicpath;

        return $this;
    }

    public function getProfilepics(): ?Profilepics
    {
        return $this->profilepics;
    }

    public function setProfilepics(?Profilepics $profilepics): self
    {
        $this->profilepics = $profilepics;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setSentBy($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSentBy() === $this) {
                $message->setSentBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSentMessage(): Collection
    {
        return $this->sent_message;
    }

    public function addSentMessage(Message $content): self
    {
        if (!$this->sent_message->contains($sent_message)) {
            $this->sent_message[] = $sent_message;
            $sent_message->setSentTo($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sent_message): self
    {
        if ($this->sent_message->removeElement($sent_message)) {
            // set the owning side to null (unless already changed)
            if ($sent_message->getSentTo() === $this) {
                $sent_message->setSentTo(null);
            }
        }

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
            $conversation->setOwnerId($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getOwnerId() === $this) {
                $conversation->setOwnerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversationsbis(): Collection
    {
        return $this->conversationsbis;
    }

    public function addConversationsbi(Conversation $conversationsbi): self
    {
        if (!$this->conversationsbis->contains($conversationsbi)) {
            $this->conversationsbis[] = $conversationsbi;
            $conversationsbi->setRecipient($this);
        }

        return $this;
    }

    public function removeConversationsbi(Conversation $conversationsbi): self
    {
        if ($this->conversationsbis->removeElement($conversationsbi)) {
            // set the owning side to null (unless already changed)
            if ($conversationsbi->getRecipient() === $this) {
                $conversationsbi->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setSentBy($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getSentBy() === $this) {
                $ticket->setSentBy(null);
            }
        }

        return $this;
    }

    public function getIsadmin(): ?bool
    {
        return $this->isadmin;
    }

    public function setIsadmin(?bool $isadmin): self
    {
        $this->isadmin = $isadmin;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriend(): Collection
    {
        return $this->friend;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friend->contains($friend)) {
            $this->friend[] = $friend;
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        $this->friend->removeElement($friend);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFriend($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFriend($this);
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUsertarget($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUsertarget() === $this) {
                $notification->setUsertarget(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name." ".$this->forename;
    }
}
