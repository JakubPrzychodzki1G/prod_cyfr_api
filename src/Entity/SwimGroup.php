<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SwimGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SwimGroupRepository::class)]
#[
    ApiResource(
        security: 'is_granted("ROLE_COACH")',
        normalizationContext: ['groups' => ['swimGroups:read']],
        denormalizationContext: ['groups' => ['swimGroups:write']],
        operations: [
            new GetCollection(
                normalizationContext: ['groups' => ['getGroups']],
                denormalizationContext: ['groups' => ['getGroups']]
            ),
            new Get(
                security: 'is_granted("ROLE_PLAYER")'
            ),
            new Post(
                security: 'is_granted("ROLE_ADMIN")'
            ),
            new Patch(),
            new Delete(
                security: 'is_granted("ROLE_ADMIN")'
            ),
            new Put()
        ]
    ),
    ApiFilter(
        SearchFilter::class,
        properties: ['coach.id' => 'exact', 'name' => 'partial']
    )
]
class SwimGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['players:read', 'swimGroups:read', 'getGroups'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['players:read', 'players:write', 'getPlayers', 'swimGroups:read', 'swimGroups:write', 'getGroups'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'swimGroups')]
    #[Groups(['swimGroups:read', 'swimGroups:write', 'getGroups'])]
    #[Assert\NotNull]
    #[Assert\Type(User::class)]
    private ?User $coach = null;

    #[ORM\ManyToMany(targetEntity: Players::class, mappedBy: 'swimGroup')]
    #[Groups(['swimGroups:read', 'swimGroups:write'])]
    private Collection $players;

    public function __construct()
    {
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

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * @return Collection<int, Players>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(?Players $player): static
    {    
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->addSwimGroup($this);
        }

        return $this;
    }

    public function removePlayer(Players $player): static
    {
        if ($this->players->removeElement($player)) {
            $player->removeSwimGroup($this);
        }

        return $this;
    }
}
