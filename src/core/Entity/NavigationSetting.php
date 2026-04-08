<?php

namespace App\Core\Entity;

use App\Core\Entity\Site\Navigation;
use App\Core\Repository\NavigationSettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NavigationSettingRepository::class)]
class NavigationSetting implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $section = null;

    #[ORM\Column(length: 255)]
    protected ?string $label = null;

    #[ORM\Column(length: 255)]
    protected ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $value = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $options = null;

    #[ORM\ManyToOne(targetEntity: Navigation::class, inversedBy: 'navigationSettings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    protected ?Navigation $navigation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValue()
    {
        return json_decode($this->value, true);
    }

    public function setValue($value): self
    {
        $this->value = json_encode($value);

        return $this;
    }

    public function getNavigation(): ?Navigation
    {
        return $this->navigation;
    }

    public function setNavigation(?Navigation $navigation): self
    {
        $this->navigation = $navigation;

        return $this;
    }

    public function getOptions()
    {
        return json_decode($this->options, true) ?? [];
    }

    public function setOptions(?array $options): self
    {
        $this->options = json_encode($options ?? []);

        return $this;
    }
}
