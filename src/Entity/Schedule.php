<?php

namespace App\Entity;

use App\_Interface\SearchableEntityInterface;
use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule implements SearchableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(mappedBy: 'schedule', targetEntity: Store::class, cascade: ['persist', 'remove'])]
    private Store $store;

    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: WorkSession::class)]
    private Collection $sessions;

    #[ORM\ManyToMany(targetEntity: WorkEvent::class, inversedBy: 'schedules')]
    private Collection $events;

    #[Pure] public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public static function getDefaultSearchFieldName(): string
    {
        return 'id';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSearchCardTitle(): string
    {
        return $this->id;
    }

    public function getSearchCardBody(): string
    {
        return "";
    }

    public function getSearchCardImage(): ?string
    {
        return null;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        // unset the owning side of the relation if necessary
        if ($store === null && $this->store !== null) {
            $this->store->setSchedule(null);
        }

        // set the owning side of the relation if necessary
        if ($store !== null && $store->getSchedule() !== $this) {
            $store->setSchedule($this);
        }

        $this->store = $store;

        return $this;
    }

    /**
     * @return Collection<int, WorkSession>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(WorkSession $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setSchedule($this);
        }

        return $this;
    }

    public function removeSession(WorkSession $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getSchedule() === $this) {
                $session->setSchedule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkEvent>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(WorkEvent $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function removeEvent(WorkEvent $event): self
    {
        $this->events->removeElement($event);

        return $this;
    }

    public function __toString(): string
    {
        return $this->store->getName();
    }
}
