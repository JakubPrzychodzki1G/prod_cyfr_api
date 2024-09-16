<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MediaObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Activities;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Controller\CreateMediaObjectAction;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MediaObjectRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    normalizationContext: ["groups" => ["media_object:read"]],
    types: ["https://schema.org/MediaObject"],
    operations: [
        new Get(),
        new Post(
            controller: CreateMediaObjectAction::class,
            deserialize: false,
            validationContext: ["groups" => ["media_object:create"]],
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        "multipart/form-data" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "file" => [
                                        "type" => "string",
                                        "format" => "binary",
                                    ],
                                    "save" => [
                                        "type" => "string",
                                        "format" => "boolean",
                                    ]
                                ],
                            ],
                        ],
                    ]),
                ),
            )
        ),
        new GetCollection(),
    ],
    // security: "is_granted('IS_PUBLIC')",
)]
class MediaObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['media_object:read', 'activitiesOne:read'])]
    private ?int $id = null;

    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    #[Groups(['media_object:read'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ["media_object:create"])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activitiesOne:read'])]
    public ?string $filePath = null;

    #[ORM\ManyToMany(targetEntity: Activities::class, inversedBy: 'image')]
    #[Groups(['media_object:read'])]
    private Collection $activities;

    #[ORM\Column]
    #[Groups(['media_object:read', 'activitiesOne:read'])]
    private bool $isTmp = true;

    private ?string $directory = null;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ApiProperty(readable: true, writable: false)]
    #[SerializedName("@id")]
    #[Groups(["activitiesOne:read"])]
    public function getIri(): ?string
    {
        return "/api/media_objects/" . $this->id;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): static
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getActivities(): ?Collection
    {
        return $this->activities;
    }

    public function addActivities(Activities $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function removeActivities(Activities $activity): static
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
        }

        return $this;
    }

    public function setDirectory(?string $directory): void
    {
        $this->directory = $directory;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function getIsTmp(): bool
    {
        return $this->isTmp;
    }

    public function setIsTmp(bool $isTmp): static
    {
        $this->isTmp = $isTmp;

        return $this;
    }
}
