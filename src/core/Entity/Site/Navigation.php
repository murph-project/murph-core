<?php

namespace App\Core\Entity\Site;

use App\Core\Doctrine\Timestampable;
use App\Core\Entity\EntityInterface;
use App\Core\Entity\NavigationSetting;
use App\Core\Repository\Site\NavigationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NavigationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Navigation implements EntityInterface
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $label = null;

    #[ORM\Column(length: 255)]
    protected ?string $code = null;

    #[ORM\Column(length: 255)]
    protected ?string $domain = null;

    #[ORM\Column(options: ['default' => 0])]
    protected bool $forceDomain = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $additionalDomains = '[]';

    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'navigation')]
    protected Collection $menus;

    #[ORM\Column(length: 10)]
    protected string $locale = 'en';

    #[ORM\Column(length: 7, nullable: true)]
    protected ?string $color = null;

    #[ORM\Column(nullable: true)]
    protected ?int $sortOrder = null;

    #[ORM\OneToMany(targetEntity: NavigationSetting::class, mappedBy: 'navigation', orphanRemoval: true)]
    protected Collection $navigationSettings;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->navigationSettings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
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

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getForceDomain(): ?bool
    {
        return $this->forceDomain;
    }

    public function setForceDomain(bool $forceDomain): self
    {
        $this->forceDomain = $forceDomain;

        return $this;
    }

    public function getAdditionalDomains(): array
    {
        return (array) json_decode($this->additionalDomains, true);
    }

    public function setAdditionalDomains(array $additionalDomains): self
    {
        $this->additionalDomains = json_encode($additionalDomains);

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setNavigation($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getNavigation() === $this) {
                $menu->setNavigation(null);
            }
        }

        return $this;
    }

    public function getMenu(string $code): ?Menu
    {
        foreach ($this->menus as $menu) {
            if ($menu->getCode() === $code) {
                return $menu;
            }
        }

        return $menu;
    }

    public function getRouteName(): string
    {
        return $this->getCode() ? $this->getCode() : 'navigation_'.$this->getId();
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * @return Collection|NavigationSetting[]
     */
    public function getNavigationSettings(): Collection
    {
        return $this->navigationSettings;
    }

    public function addNavigationSetting(NavigationSetting $navigationSetting): self
    {
        if (!$this->navigationSettings->contains($navigationSetting)) {
            $this->navigationSettings[] = $navigationSetting;
            $navigationSetting->setNavigation($this);
        }

        return $this;
    }

    public function removeNavigationSetting(NavigationSetting $navigationSetting): self
    {
        if ($this->navigationSettings->removeElement($navigationSetting)) {
            // set the owning side to null (unless already changed)
            if ($navigationSetting->getNavigation() === $this) {
                $navigationSetting->setNavigation(null);
            }
        }

        return $this;
    }

    public function matchDomain(string $domain): bool
    {
        if ($domain === $this->getDomain()) {
            return true;
        }

        foreach ($this->getAdditionalDomains() as $additionalDomain) {
            if ('domain' === $additionalDomain['type'] && $additionalDomain['domain'] === $domain) {
                return true;
            }

            if ('regexp' === $additionalDomain['type'] && preg_match('#'.$additionalDomain['domain'].'#', $domain) > 0) {
                return true;
            }
        }

        return false;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
