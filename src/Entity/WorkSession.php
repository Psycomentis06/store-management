<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\WorkSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: WorkSessionRepository::class)]
class WorkSession implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $fromTime;

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $toTime;

    #[ORM\Column(type: 'array')]
    private array $days = [];

    #[ORM\ManyToOne(targetEntity: Schedule::class, inversedBy: 'sessions')]
    private Schedule $schedule;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'workSessions')]
    private Collection $users;

    #[Pure] public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->fromTime = new \DateTime();
        $this->toTime = new \DateTime();
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'id';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromTime(): ?\DateTimeInterface
    {
        return $this->fromTime;
    }

    public function setFromTime(\DateTimeInterface $fromTime): self
    {
        $this->fromTime = $fromTime;

        return $this;
    }

    public function getToTime(): ?\DateTimeInterface
    {
        return $this->toTime;
    }

    public function setToTime(\DateTimeInterface $toTime): self
    {
        $this->toTime = $toTime;

        return $this;
    }

    public function getDays(): ?array
    {
        return $this->days;
    }

    public function setDays(array $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getSearchCardTitle(): string
    {
        return $this->fromTime . ' => ' . $this->toTime;
    }

    public function getSearchCardBody(): string
    {
        return '';
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
