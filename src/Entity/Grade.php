<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\GradeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
#[
    ApiResource(
        security: 'is_granted("ROLE_COACH")',
        operations: [
            new Get(
                security: 'is_granted("ROLE_PLAYER")'
            ),
            new GetCollection(),
            new Post(
                validationContext: ['groups' => ['Default', 'postValidation']]
            ),
            new Patch(),
            new Put(),
            new Delete()
        ]
    ),
    ApiFilter(
        SearchFilter::class,
        properties: ['player.id' => 'exact']
    ),
]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['players:grades:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['players:grades:read'])]
    #[Assert\NotBlank]
    private ?string $value = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['players:grades:read'])]
    #[Assert\Type('float')]
    #[Assert\PositiveOrZero]
    private ?float $wage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['players:grades:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['players:grades:read'])]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThanOrEqual('-10 minutes', groups: ['postValidation'])]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['players:grades:read'])]
    #[Assert\Type(type: 'DateTime', groups: ['postValidation'])]
    #[Assert\GreaterThanOrEqual('-10 minutes', groups: ['postValidation'])]
    private ?\DateTimeInterface $modTime = null;

    #[ORM\Column]
    private ?bool $isDeleted = false;

    #[ORM\Column(nullable: true)]
    private ?bool $isArchived = false;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Assert\Type(Players::class)]
    private ?Players $player = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['players:grades:read'])]
    #[Assert\NotNull]
    #[Assert\Type(User::class)]
    private ?User $addedBy = null;

    public function __construct()
    {
        $this->createDate = new \DateTime("now");
        $this->modTime = new \DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getWage(): ?float
    {
        return $this->wage;
    }

    public function setWage(float $wage): static
    {
        $this->wage = $wage;

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

    public function getCreateDate(): ?\DateTimeInterface
    {

        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate = new \DateTime("now")): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getModTime(): ?\DateTimeInterface
    {
        return $this->modTime;
    }

    public function setModTime(?\DateTimeInterface $modTime): static
    {
        $this->modTime = new \DateTimeImmutable();

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

    public function isIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(?bool $isArchived): static
    {
        $this->isArchived = $isArchived;

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

    public function getAddedBy(): ?User
    {
        return $this->addedBy;
    }

    public function setAddedBy(?User $addedBy): static
    {
        $this->addedBy = $addedBy;

        return $this;
    }
}
