<?php

namespace App\Core\Entity\Analytic;

use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Node;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'analytic_referer')]
#[ORM\Entity(repositoryClass: ViewRepository::class)]
class Referer implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Node::class, inversedBy: 'analyticReferers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    protected ?Node $node = null;

    #[ORM\Column(length: 255)]
    protected ?string $uri = null;

    #[ORM\Column(options: ['default' => 0])]
    protected int $views = 0;

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

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

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
