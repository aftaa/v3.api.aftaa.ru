<?php

namespace App\Entity;

use App\Repository\BlockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BlockRepository::class)]
class Block
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api', 'block'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['api', 'block'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['api', 'block'])]
    private ?int $col = null;

    #[ORM\Column]
    #[Groups(['api', 'block'])]
    private ?int $sort = null;

    #[ORM\Column]
    #[Groups(['api', 'block'])]
    private ?bool $private = null;

    #[ORM\Column]
    #[Groups(['api', 'block'])]
    private ?bool $deleted = null;

    #[ORM\OneToMany(mappedBy: 'block', targetEntity: Link::class, cascade: ['persist'], indexBy: 'id')]
    private Collection $links;

    public function __construct()
    {
        $this->links = new ArrayCollection();
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

    public function getCol(): ?int
    {
        return $this->col;
    }

    public function setCol(int $col): static
    {
        $this->col = $col;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection<int, Link>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Link $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setBlock($this);
        }

        return $this;
    }

    public function removeLink(Link $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getBlock() === $this) {
                $link->setBlock(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'col' => $this->getCol(),
            'sort' => $this->getSort(),
            'deleted' => $this->isDeleted(),
            'private' => $this->isPrivate(),
            'links' => [],
        ];
    }
}
