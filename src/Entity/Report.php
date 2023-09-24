<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'report', targetEntity: ReportRow::class)]
    private Collection $reportRows;

    public function __construct()
    {
        $this->reportRows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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
            $reportRow->setReport($this);
        }

        return $this;
    }

    public function removeReportRow(ReportRow $reportRow): static
    {
        if ($this->reportRows->removeElement($reportRow)) {
            // set the owning side to null (unless already changed)
            if ($reportRow->getReport() === $this) {
                $reportRow->setReport(null);
            }
        }

        return $this;
    }
}
