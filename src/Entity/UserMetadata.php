<?php

namespace App\Entity;

use App\Repository\UserMetadataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserMetadataRepository::class)]
class UserMetadata
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'json', nullable: true)]
    private $prefs = [];

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $lang;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefs(): ?array
    {
        return $this->prefs;
    }

    public function setPrefs(?array $prefs): self
    {
        $this->prefs = $prefs;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }
}
