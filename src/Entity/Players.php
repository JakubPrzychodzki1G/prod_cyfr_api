<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PlayersRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use ApiPlatform\Serializer\Filter\GroupFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Filter\NotInFilter;
use App\State\PlayersProcessor;

#[ORM\Entity(repositoryClass: PlayersRepository::class)]
#[
    ApiResource(
        security: 'is_granted("ROLE_COACH")',
        operations: [
            new Get(),
            new Post(
                security: 'is_granted("PUBLIC_ACCESS")',
                validationContext: ['groups' => ['Default', 'postValidation']],
                processor: PlayersProcessor::class
            ),
            new GetCollection(
                normalizationContext: ['groups' => ['getPlayers']],
                security: 'is_granted("ROLE_PLAYER") or is_granted("ROLE_PARENT")'
            ),
            new Patch(
                security: 'is_granted("ROLE_ADMIN")'
            ),
            new Delete(
                security: 'is_granted("ROLE_ADMIN")'
            )
        ],
        normalizationContext: ['groups' => ['players:read']],
        denormalizationContext: ['groups' => ['players:write']],
        
    ),
    ApiFilter(
        BooleanFilter::class,
        properties: ['isVerified', 'isDeleted', 'grades.isArchived']
    ),
    ApiFilter(
        ExistsFilter::class,
        properties: ['swimGroup']
    ),
    ApiFilter(
        SearchFilter::class,
        properties: ['swimGroup.id' => 'exact', 'firstName' => 'partial', 'lastName' => 'partial', 'parentFirstName' => 'partial', 'parentLastName' => 'partial', 'parent2FirstName' => 'partial', 'parent2LastName' => 'partial', 'contactEmail' => 'partial', 'mainNumber' => 'partial', 'additionalNumber' => 'partial', 'playerNumber' => 'partial', 'skill' => 'exact', 'section' => 'exact']
    ),
    ApiFilter(
        NotInFilter::class,
        properties: ['id']
    ),
    ApiFilter(
        GroupFilter::class,
        arguments: ['parameterName' => 'groups', 'overrideDefaultGroups' => false, 'whitelist' => ['players:grades:read']]
    ),
    ApiFilter(
        PropertyFilter::class, 
        arguments: [
            'parameterName' => 'properties',
            'overrideDefaultProperties' => true, 
            'whitelist' => [
                'id',
                'firstName',
                'lastName',
                'grades',
                'grades.id',
                'grades.value',
                'grades.wage',
                'grades.description',
                'grades.createDate',
                'grades.modTime',
                'grades.user.id',
                'grades.user.name',
                'grades.user.lastName'                
            ]
        ]
    )
]
class Players
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['players:read', 'players:write', 'getPlayers', 'players:grades:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max: 50, minMessage: "Too short", maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max: 80, minMessage: "Too short", maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?int $sex = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\LessThanOrEqual('-4 years', groups: ['postValidation'])]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write'])]
    private ?string $schoolName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max: 80, minMessage: "Too short",maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $city = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max: 100, minMessage: "Too short",maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $streetAndNumber = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 6,max: 6,maxMessage:"Zip code should have 6 digits")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $zipCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max: 50, minMessage: "Too short",maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $parentFirstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max: 80, minMessage: "Too short",maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $parentLastName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 50, maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write'])]
    private ?string $parent2FirstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 80, maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write'])]
    private ?string $parent2LastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5,max: 255, minMessage: "Too short",maxMessage:"Too long")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 9,max: 9,maxMessage:"Phone number must have 9 digits")]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $mainNumber = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['players:read', 'players:write'])]
    private ?string $additionalNumber = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['players:read', 'players:write'])]
    private ?string $playerNumber = null;

    #[ORM\Column]
    #[Groups(['players:read'])]
    private ?bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleteDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['players:read', 'players:write'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThanOrEqual('-10 minutes', groups: ['postValidation'])]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(type: 'DateTime')]
    #[Assert\GreaterThanOrEqual('-10 minutes', groups: ['postValidation'])]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column]
    #[Groups(['players:read', 'getPlayers'])]
    private ?bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    #[Assert\Length(max: 255)]
    private ?string $skill = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $comments = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private ?string $section = null;

    #[ORM\ManyToMany(targetEntity: SwimGroup::class, inversedBy: 'players')]
    #[Groups(['players:read', 'players:write', 'getPlayers'])]
    private Collection $swimGroup;

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Attendance::class, orphanRemoval: true)]
    private Collection $attendances;

    #[ORM\ManyToMany(targetEntity: Lesson::class, mappedBy: 'players')]
    private Collection $lessons;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Grade::class, orphanRemoval: true)]
    #[Groups(['players:grades:read'])]
    private Collection $grades;

    #[ORM\OneToOne(mappedBy: 'player', cascade: ['persist', 'remove'])]
    private ?User $connectedUser = null;

    public function __construct()
    {
        $this->creationDate = new DateTime('now');
        $this->swimGroup = new ArrayCollection();
        $this->attendances = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->grades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSchoolName(): ?string
    {
        return $this->schoolName;
    }

    public function setSchoolName(?string $schoolName): static
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStreetAndNumber(): ?string
    {
        return $this->streetAndNumber;
    }

    public function setStreetAndNumber(?string $streetAndNumber): static
    {
        $this->streetAndNumber = $streetAndNumber;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getParentFirstName(): ?string
    {
        return $this->parentFirstName;
    }

    public function setParentFirstName(?string $parentFirstName): static
    {
        $this->parentFirstName = $parentFirstName;

        return $this;
    }

    public function getParentLastName(): ?string
    {
        return $this->parentLastName;
    }

    public function setParentLastName(?string $parentLastName): static
    {
        $this->parentLastName = $parentLastName;

        return $this;
    }

    public function getParent2FirstName(): ?string
    {
        return $this->parent2FirstName;
    }

    public function setParent2FirstName(?string $parent2FirstName): static
    {
        $this->parent2FirstName = $parent2FirstName;

        return $this;
    }

    public function getParent2LastName(): ?string
    {
        return $this->parent2LastName;
    }

    public function setParent2LastName(?string $parent2LastName): static
    {
        $this->parent2LastName = $parent2LastName;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getMainNumber(): ?string
    {
        return $this->mainNumber;
    }

    public function setMainNumber(?string $mainNumber): static
    {
        $this->mainNumber = $mainNumber;

        return $this;
    }

    public function getAdditionalNumber(): ?string
    {
        return $this->additionalNumber;
    }

    public function setAdditionalNumber(?string $additionalNumber): static
    {
        $this->additionalNumber = $additionalNumber;

        return $this;
    }

    public function getPlayerNumber(): ?string
    {
        return $this->playerNumber;
    }

    public function setPlayerNumber(?string $playerNumber): static
    {
        $this->playerNumber = $playerNumber;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDeleteDate(): ?\DateTimeInterface
    {
        return $this->deleteDate;
    }

    public function setDeleteDate(?\DateTimeInterface $deleteDate): static
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = new DateTime('now');

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getSkill(): ?string
    {
        return $this->skill;
    }

    public function setSkill(?string $skill): static
    {
        $this->skill = $skill;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): static
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, SwimGroup>
     */
    public function getSwimGroup(): Collection
    {
        return $this->swimGroup;
    }

    public function addSwimGroup(SwimGroup $swimGroup): static
    {
        if (!$this->swimGroup->contains($swimGroup)) {
            $this->swimGroup->add($swimGroup);
        }

        return $this;
    }

    public function removeSwimGroup(SwimGroup $swimGroup): static
    {
        $this->swimGroup->removeElement($swimGroup);

        return $this;
    }

    /**
     * @return Collection<int, Attendance>
     */
    public function getAttendances(): Collection
    {
        return $this->attendances;
    }

    public function addAttendance(Attendance $attendance): static
    {
        if (!$this->attendances->contains($attendance)) {
            $this->attendances->add($attendance);
            $attendance->setPlayer($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getPlayer() === $this) {
                $attendance->setPlayer(null);
            }
        }

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
            $lesson->addPlayer($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            $lesson->removePlayer($this);
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
            $grade->setPlayer($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getPlayer() === $this) {
                $grade->setPlayer(null);
            }
        }

        return $this;
    }

    public function getConnectedUser(): ?User
    {
        return $this->connectedUser;
    }

    public function setConnectedUser(?User $connectedUser): static
    {
        // unset the owning side of the relation if necessary
        if ($connectedUser === null && $this->connectedUser !== null) {
            $this->connectedUser->setPlayer(null);
        }

        // set the owning side of the relation if necessary
        if ($connectedUser !== null && $connectedUser->getPlayer() !== $this) {
            $connectedUser->setPlayer($this);
        }

        $this->connectedUser = $connectedUser;

        return $this;
    }
}
