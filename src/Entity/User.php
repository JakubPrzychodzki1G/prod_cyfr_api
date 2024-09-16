<?php

namespace App\Entity;

use ApiPlatform\Doctrine\ORM\Filter\BooleanFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\UserHashPasswordProcessor;
use ApiPlatform\Metadata\ApiFilter;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[
    ApiResource(
        security: 'is_granted("ROLE_ADMIN")',
        normalizationContext: ['groups' => ['user:read']],
        denormalizationContext: ['groups' => ['user:write']],
        operations: [
            new Get(
                security: 'is_granted("ROLE_COACH")',
            ),
            new GetCollection(
                security: 'is_granted("ROLE_PLAYER")',
            ),
            new Post(),
            new Delete(),
            new Patch()
        ]
    ),
    ApiFilter(
        BooleanFilter::class,
        properties: ['isCoach', 'isDeleted']
    ),
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'swimGroups:read', 'getGroups', 'players:grades:read', 'player:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:write', 'swimGroups:read', 'getGroups'])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['user:write'])]
    #[SerializedName('password')]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'swimGroups:read', 'getGroups', 'getLessons', 'players:grades:read', 'player:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'swimGroups:read', 'getGroups', 'getLessons', 'players:grades:read', 'player:read'])]
    private ?string $lastName = null;

    #[ORM\Column]
    #[Groups(['user:write'])]
    private ?bool $isVerified = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDeleted = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleteDate = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $mobileNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastFailedLogin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\OneToMany(mappedBy: 'coach', targetEntity: SwimGroup::class)]
    private Collection $swimGroups;

    #[ORM\Column(nullable: true)]
    private ?bool $isCoach = null;

    #[ORM\OneToMany(mappedBy: 'coach', targetEntity: Lesson::class)]
    private Collection $lessons;

    #[ORM\OneToMany(mappedBy: 'addedBy', targetEntity: Grade::class, orphanRemoval: true)]
    private Collection $grades;

    #[ORM\OneToOne(inversedBy: 'connectedUser', cascade: ['persist', 'remove'])]
    private ?Players $player = null;

    public function __construct()
    {
        $this->swimGroups = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->grades = new ArrayCollection();
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
    public function getUserIdentifier(): string
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDeleteDate(): ?\DateTimeInterface
    {
        return $this->deleteDate;
    }

    public function setDeleteDate(?\DateTimeInterface $deleteDate): self
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(?string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getLastFailedLogin(): ?\DateTimeInterface
    {
        return $this->lastFailedLogin;
    }

    public function setLastFailedLogin(?\DateTimeInterface $lastFailedLogin): self
    {
        $this->lastFailedLogin = $lastFailedLogin;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @return Collection<int, SwimGroup>
     */
    public function getSwimGroups(): Collection
    {
        return $this->swimGroups;
    }

    public function addSwimGroup(SwimGroup $swimGroup): static
    {
        if (!$this->swimGroups->contains($swimGroup)) {
            $this->swimGroups->add($swimGroup);
            $swimGroup->setCoach($this);
        }

        return $this;
    }

    public function removeSwimGroup(SwimGroup $swimGroup): static
    {
        if ($this->swimGroups->removeElement($swimGroup)) {
            // set the owning side to null (unless already changed)
            if ($swimGroup->getCoach() === $this) {
                $swimGroup->setCoach(null);
            }
        }

        return $this;
    }

    public function isIsCoach(): ?bool
    {
        return $this->isCoach;
    }

    public function setIsCoach(?bool $isCoach): static
    {
        $this->isCoach = $isCoach;

        return $this;
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setCoach($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getCoach() === $this) {
                $lesson->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Grade>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setAddedBy($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getAddedBy() === $this) {
                $grade->setAddedBy(null);
            }
        }

        return $this;
    }

    public function getPlayer(): ?Players
    {
        return $this->player;
    }

    public function setPlayer(?Players $player): static
    {
        $this->player = $player;

        return $this;
    }
}
