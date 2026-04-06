<?php

namespace App\Core\Entity;

use App\Repository\Entity\FileInformationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileInformationRepository::class)]
class FileInformation implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(length: 96, unique: true)]
    protected ?string $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $attributes = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAttributes()
    {
        return (array) json_decode($this->attributes, true);
    }

    public function setAttributes($attributes): self
    {
        $this->attributes = json_encode($attributes);

        return $this;
    }
}
