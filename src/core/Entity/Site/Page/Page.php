<?php

namespace App\Core\Entity\Site\Page;

use App\Core\Doctrine\Timestampable;
use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Node;
use App\Core\File\FileAttribute;
use App\Core\Repository\Site\Page\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormBuilderInterface;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\DiscriminatorColumn(name: 'class_key', type: 'string')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\HasLifecycleCallbacks]
class Page implements EntityInterface
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $template = null;

    #[ORM\OneToMany(targetEntity: Block::class, mappedBy: 'page', cascade: ['persist'])]
    protected Collection $blocks;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $metaTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $metaDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $ogTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $ogDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $ogImage = null;

    #[ORM\OneToMany(targetEntity: Node::class, mappedBy: 'page')]
    protected Collection $nodes;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
        $this->nodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Block[]|Collection
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setPage($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->removeElement($block)) {
            // set the owning side to null (unless already changed)
            if ($block->getPage() === $this) {
                $block->setPage(null);
            }
        }

        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    }

    public function getBlock($name, string $className = null)
    {
        foreach ($this->getBlocks() as $block) {
            if ($block->getName() === $name) {
                return $block;
            }
        }

        if ($className) {
            $block = new $className();
        } else {
            $block = new Block();
        }

        $block->setName($name);
        $block->setPage($this);

        return $block;
    }

    public function setBlock(Block $block): self
    {
        foreach ($this->blocks->toArray() as $key => $value) {
            if ($value->getName() === $block->getName()) {
                $this->blocks->remove($key);
                $this->blocks->add($block);

                return $this;
            }
        }

        $this->blocks->add($block);

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgTitle(?string $ogTitle): self
    {
        $this->ogTitle = $ogTitle;

        return $this;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;

        return $this;
    }

    public function getOgImage()
    {
        return FileAttribute::handleFile($this->ogImage);
    }

    public function setOgImage($ogImage): self
    {
        if (null !== $this->ogImage && null === $ogImage) {
            return $this;
        }

        $this->ogImage = $ogImage;

        return $this;
    }

    /**
     * @return Collection|Node[]
     */
    public function getNodes(): Collection
    {
        return $this->nodes;
    }

    public function addNode(Node $node): self
    {
        if (!$this->nodes->contains($node)) {
            $this->nodes[] = $node;
            $node->setPage($this);
        }

        return $this;
    }

    public function removeNode(Node $node): self
    {
        if ($this->nodes->removeElement($node)) {
            // set the owning side to null (unless already changed)
            if ($node->getPage() === $this) {
                $node->setPage(null);
            }
        }

        return $this;
    }
}
