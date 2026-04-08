<?php

namespace App\Core\Entity\Analytic;

use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Node;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'analytic_view')]
#[ORM\Entity(repositoryClass: ViewRepository::class)]
class View implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Node::class, inversedBy: 'analyticViews')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    protected ?Node $node = null;

    #[ORM\Column(length: 255)]
    protected ?string $path = null;

    #[ORM\Column(options: ['default' => 0])]
    protected int $views = 0;

    #[ORM\Column(options: ['default' => 0])]
    protected int $desktopViews = 0;

    #[ORM\Column(options: ['default' => 0])]
    protected int $mobileViews = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    protected ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }

    public function setNode(?Node $node): self
    {
        $this->node = $node;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function addView(): self
    {
        ++$this->views;

        return $this;
    }

    public function getDesktopViews(): ?int
    {
        return $this->desktopViews;
    }

    public function setDesktopViews(int $desktopViews): self
    {
        $this->desktopViews = $desktopViews;

        return $this;
    }

    public function addDesktopView(): self
    {
        ++$this->desktopViews;

        return $this;
    }

    public function getMobileViews(): ?int
    {
        return $this->mobileViews;
    }

    public function setMobileViews(int $mobileViews): self
    {
        $this->mobileViews = $mobileViews;

        return $this;
    }

    public function addMobileView(): self
    {
        ++$this->mobileViews;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
