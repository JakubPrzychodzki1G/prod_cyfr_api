<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use App\Repository\ActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use App\Filter\NotInFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[ORM\Entity(repositoryClass: ActivitiesRepository::class)]
#[
    ApiResource(
        security: 'is_granted("ROLE_COACH")',
        shortName: 'activities', 
        paginationItemsPerPage: 9, 
        order: ['creationDate' => 'DESC'],
        operations: [
            new GetCollection(
                security: 'is_granted("PUBLIC_ACCESS")'
            ),
            new Get(
                security: 'is_granted("PUBLIC_ACCESS")',
                normalizationContext: ['groups' => ['activities:read', 'activitiesOne:read']]
            ),
            new Post(
                security: 'is_granted("ROLE_COACH")'
            ),
            new Put(
                security: 'is_granted("ROLE_COACH")'
            ),
            new Delete(
                security: 'is_granted("ROLE_ADMIN")'
            ),
            new Patch(
                security: 'is_granted("ROLE_COACH")'
            )

        ],
        normalizationContext: ['groups' => ['activities:read']],
        denormalizationContext: ['groups' => ['activities:write']]
    ),
    ApiResource(
        shortName: 'mobile_activities', 
        paginationItemsPerPage: 6, 
        order: ['creationDate' => 'DESC'],
        operations: [
            new GetCollection(
                security: 'is_granted("PUBLIC_ACCESS")'
            )
        ],
        normalizationContext: ['groups' => ['mobile_activities:read']],
        denormalizationContext: ['groups' => ['mobile_activities:write']]
    ),
    ApiResource(
        security: 'is_granted("ROLE_COACH")',
        shortName: 'new_activities', 
        paginationItemsPerPage: 3, 
        order: ['creationDate' => 'DESC'],
        operations: [
            new GetCollection(
                security: 'is_granted("PUBLIC_ACCESS")'
            )
        ],
        normalizationContext: ['groups' => ['new_activities:read']],
        denormalizationContext: ['groups' => ['new_activities:write']]
    ),
    ApiFilter(
        BooleanFilter::class,
        properties: ['is_deleted']
    ),
    ApiFilter(
        NotInFilter::class,
        properties: ['id']
    ),
    ApiFilter(
        SearchFilter::class,
        properties: ['title' => 'partial']
    ),
    ApiFilter(
        DateFilter::class,
        properties: ['date']
    )
]
class Activities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private ?string $customHref = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(length: 2555, nullable: true)]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private ?string $titleImage = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleteDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToMany(targetEntity: MediaObject::class, mappedBy: 'activities')]
    #[Groups(['activities:read', 'activities:write', 'mobile_activities:read', 'new_activities:read', 'mobile_activities:write', 'new_activities:write'])]
    private Collection $image;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->isDeleted = false;
        $this->image = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomHref(): ?string
    {
        return $this->customHref;
    }

    public function setCustomHref(?string $customHref): static
    {
        $this->customHref = $customHref;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

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

    public function getTitleImage(): ?string
    {
        return $this->titleImage;
    }

    public function setTitleImage(?string $titleImage): static
    {
        $this->titleImage = $titleImage;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $is_deleted): static
    {
        $this->isDeleted = $is_deleted;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(MediaObject $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->addActivities($this);
        }

        return $this;
    }

    public function removeImage(MediaObject $image): static
    {
        if ($this->image->removeElement($image)) {
            $image->removeActivities($this);
        }

        return $this;
    }
}
