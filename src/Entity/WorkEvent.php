<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\WorkEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: WorkEventRepository::class)]
class WorkEvent implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $fromDate;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $toDate;

    #[ORM\Column(type: 'string', length: 30)]
    private string $type;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $description;

    #[ORM\ManyToMany(targetEntity: Schedule::class, mappedBy: 'events')]
    private Collection $schedules;

    #[Pure] public function __construct()
    {
        $this->schedules = new ArrayCollection();
        $this->fromDate = new \DateTime();
        $this->toDate = new \DateTime();
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'title';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSearchCardTitle(): string
    {
        return $this->title;
    }

    public function getSearchCardBody(): string
    {
        return $this->description;
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->addEvent($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->removeElement($schedule)) {
            $schedule->removeEvent($this);
        }

        return $this;
    }
}
