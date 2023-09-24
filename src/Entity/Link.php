<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'links')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Block $block = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $href = null;

    #[ORM\Column(length: 100)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    #[ORM\OneToMany(mappedBy: 'link', targetEntity: ReportRow::class)]
    private Collection $reportRows;

    #[ORM\OneToMany(mappedBy: 'link', targetEntity: View::class)]
    private Collection $views;

    public function __construct()
    {
        $this->reportRows = new ArrayCollection();
        $this->views = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function setBlock(?Block $block): static
    {
        $this->block = $block;

        return $this;
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

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(string $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

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
     * @return Collection<int, ReportRow>
     */
    public function getReportRows(): Collection
    {
        return $this->reportRows;
    }

    public function addReportRow(ReportRow $reportRow): static
    {
        if (!$this->reportRows->contains($reportRow)) {
            $this->reportRows->add($reportRow);
            $reportRow->setLink($this);
        }

        return $this;
    }

    public function removeReportRow(ReportRow $reportRow): static
    {
        if ($this->reportRows->removeElement($reportRow)) {
            // set the owning side to null (unless already changed)
            if ($reportRow->getLink() === $this) {
                $reportRow->setLink(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, View>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(View $view): static
    {
        if (!$this->views->contains($view)) {
            $this->views->add($view);
            $view->setLink($this);
        }

        return $this;
    }

    public function removeView(View $view): static
    {
        if ($this->views->removeElement($view)) {
            // set the owning side to null (unless already changed)
            if ($view->getLink() === $this) {
                $view->setLink(null);
            }
        }

        return $this;
    }
}
