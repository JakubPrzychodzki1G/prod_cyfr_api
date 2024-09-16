<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\SettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
#[
    ApiResource(
        operations: [
            new Get (
                security: 'is_granted("PUBLIC_ACCESS")',
                normalizationContext: ['groups' => ['settings:read']]
            ),
            new GetCollection(
                security: 'is_granted("PUBLIC_ACCESS")',
            ),
            new Patch (
                security: 'is_granted("ROLE_ADMIN")',
            ),
            new Put(
                security: 'is_granted("ROLE_ADMIN")',
            )
        ],
        paginationEnabled: false,
        denormalizationContext: ['groups' => ['settings:write']]
    )
]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['settings:read'])]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['settings:read'])]
    #[ApiProperty(identifier: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['settings:read', 'settings:write'])]
    private ?array $value = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[ApiProperty(security: 'is_granted("ROLE_ADMIN")')]
    #[Groups(['settings:write'])]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[ApiProperty(security: 'is_granted("ROLE_ADMIN")')]
    #[Groups(['settings:write'])]
    #[Assert\Length(max: 255)]
    private ?string $description = null;

    #[ORM\Column]
    #[ApiProperty(security: 'is_granted("ROLE_ADMIN")')]
    private ?bool $isEditable = null;

    #[ORM\Column]
    #[ApiProperty(security: 'is_granted("ROLE_ADMIN")')]
    private ?bool $isLogged = null;

    #[ORM\Column(nullable: true)]
    #[ApiProperty(security: 'is_granted("ROLE_ADMIN")')]
    private ?bool $isPublic = null;

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

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): static
    {
        $this->value = $value;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isIsEditable(): ?bool
    {
        return $this->isEditable;
    }

    public function setIsEditable(bool $isEditable): static
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    public function isIsLogged(): ?bool
    {
        return $this->isLogged;
    }

    public function setIsLogged(bool $isLogged): static
    {
        $this->isLogged = $isLogged;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}
