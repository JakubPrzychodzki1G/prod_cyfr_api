<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\State\LessonProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
#[
    ApiResource(
        security: 'is_granted("ROLE_COACH")',
        operations: [
            new Get(
                security: 'is_granted("ROLE_PLAYER")'
            ),
            new GetCollection(
                security: 'is_granted("ROLE_PLAYER")',
                normalizationContext: ['groups' => ['getLessons']],
            ),
            new Post(
                security: 'is_granted("ROLE_COACH")',
                validationContext: ['groups' => ['Default', 'postValidation']]
            ),
            new Patch(
                security: 'is_granted("ROLE_COACH")',
                processor: LessonProcessor::class
            ),
            new Delete(
                security: 'is_granted("ROLE_COACH")'
            )
        ],
        normalizationContext: ['groups' => ['lessons:read']],
        denormalizationContext: ['groups' => ['lessons:write']],
    ),
    ApiFilter(
        SearchFilter::class,
        properties: ['coach.id' => 'exact', 'name' => 'partial', 'pool' => 'partial', 'fees' => 'partial', 'ageGroup' => 'partial']
    ),
    ApiFilter(
        BooleanFilter::class,
        properties: ['isInvidual', 'isDeleted']
    ),
    ApiFilter(
        DateFilter::class,
        properties: ['startDateTime', 'endDateTime']
    ),
    ApiFilter(
        NumericFilter::class,
        properties: ['duration']
    )

]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['players:read', 'lessons:read', 'getLessons'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    private ?string $description = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'lessons')]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotNull]
    #[Assert\Type(User::class)]
    private ?User $coach = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    #[Assert\Length(max: 255)]
    private ?string $skillLevel = null;

    #[ORM\Column(length: 255)]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $pool = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    #[Assert\Length(max: 1024)]
    private ?string $equipment = null;

    #[ORM\Column]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Type('float')]
    private ?float $duration = null;

    #[ORM\Column]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotNull]
    #[Assert\Type('bool')]
    private ?bool $isInvidual = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\Length(max: 255)]
    private ?string $ageGroup = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    #[Assert\Length(max: 1024)]
    private ?string $objectives = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    #[Assert\Length(max: 255)]
    private ?string $fees = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThanOrEqual('today', groups: ['postValidation'])]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['lessons:read', 'lessons:write', 'getLessons'])]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThan(propertyPath: 'startDateTime', groups: ['postValidation'])]
    private ?\DateTimeInterface $endDateTime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    private ?string $comments = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleteDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThanOrEqual('-10 minutes', groups: ['postValidation'])]
    private ?\DateTimeInterface $modDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThanOrEqual('-10 minutes', groups: ['postValidation'])]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Attendance::class, orphanRemoval: true)]
    #[Groups(['lessons:read', 'lessons:write'])]
    private Collection $attendances;

    #[ORM\ManyToMany(targetEntity: Players::class, inversedBy: 'lessons')]
    #[Groups(['lessons:read', 'lessons:write'])]
    private Collection $players;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
        $this->players = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getSkillLevel(): ?string
    {
        return $this->skillLevel;
    }

    public function setSkillLevel(?string $skillLevel): static
    {
        $this->skillLevel = $skillLevel;

        return $this;
    }

    public function getPool(): ?string
    {
        return $this->pool;
    }

    public function setPool(string $pool): static
    {
        $this->pool = $pool;

        return $this;
    }

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(?string $equipment): static
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function isIsInvidual(): ?bool
    {
        return $this->isInvidual;
    }

    public function setIsInvidual(bool $isInvidual): static
    {
        $this->isInvidual = $isInvidual;

        return $this;
    }

    public function getAgeGroup(): ?string
    {
        return $this->ageGroup;
    }

    public function setAgeGroup(?string $ageGroup): static
    {
        $this->ageGroup = $ageGroup;

        return $this;
    }

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(?string $objectives): static
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getFees(): ?string
    {
        return $this->fees;
    }

    public function setFees(?string $fees): static
    {
        $this->fees = $fees;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(\DateTimeInterface $endDateTime): static
    {
        $this->endDateTime = $endDateTime;

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

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDeleteDateTime(): ?\DateTimeInterface
    {
        return $this->deleteDateTime;
    }

    public function setDeleteDateTime(?\DateTimeInterface $deleteDateTime): static
    {
        $this->deleteDateTime = $deleteDateTime;

        return $this;
    }

    public function getModDateTime(): ?\DateTimeInterface
    {
        return $this->modDateTime;
    }

    public function setModDateTime(?\DateTimeInterface $modDateTime): static
    {
        $this->modDateTime = $modDateTime;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

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
            $attendance->setLesson($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getLesson() === $this) {
                $attendance->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Players>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Players $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }

        return $this;
    }

    public function removePlayer(Players $player): static
    {
        $this->players->removeElement($player);

        return $this;
    }
}
