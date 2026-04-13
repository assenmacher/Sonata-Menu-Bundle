<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Model\SiteInterface;
use App\Sonata\PageBundle\Model\SiteInterface as AppSonataSiteInterface;

/**
 * Menu
 *
 * @ORM\Table(name="sonata_menu")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
abstract class Menu implements MenuInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    protected $alias;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true, options={"default":true})
     */
    protected $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="locale_enabled", type="boolean", nullable=true, options={"default":true})
     * @Gedmo\Translatable
     */
    protected $localeEnabled;

    /**
     * @var AppSonataSiteInterface

     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\SiteInterface")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $site;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface", mappedBy="menu", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $menuItems;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_megamenu", type="boolean", nullable=true, options={"default":false})
     */
    protected $isMegamenu;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MenuInterface
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return MenuInterface
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return null|string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * Set site
     *
     * @param null|AppSonataSiteInterface $site
     *
     * @return MenuInterface
     */
    public function setSite(?AppSonataSiteInterface $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return null|AppSonataSiteInterface
     */
    public function getSite(): ?AppSonataSiteInterface
    {
        return $this->site;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return MenuInterface
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return null|boolean
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * Get locale enabled
     *
     * @return null|boolean
     */
    public function getLocaleEnabled(): ?bool
    {
        return $this->localeEnabled;
    }


    /**
     * Set locale enabled
     *
     * @param boolean $enabled
     *
     * @return MenuInterface
     */
    public function setLocaleEnabled(bool $localeEnabled): self
    {
        $this->localeEnabled = $localeEnabled;

        return $this;
    }

    /**
     * Add menuItem
     *
     * @param MenuItemInterface $menuItem
     *
     * @return MenuInterface
     */
    public function addMenuItem(MenuItemInterface $menuItem): self
    {
        $this->menuItems->add($menuItem);

        $menuItem->setMenu($this);

        return $this;
    }

    /**
     * Remove menuItem
     *
     * @param MenuItemInterface $menuItem
     *
     * @return MenuInterface
     */
    public function removeMenuItem(MenuItemInterface $menuItem): self
    {
        $this->menuItems->removeElement($menuItem);

        return $this;
    }

    /**
     * Set menuItems
     *
     * @param ArrayCollection $menuItems
     *
     * @return MenuInterface
     */
    public function setMenuItems(ArrayCollection $menuItems): self
    {
        $this->menuItems = $menuItems;

        return $this;
    }

    /**
     * Get menuItems
     *
     * @return array
     */
    public function getMenuItems(): array
    {   
        return $this->menuItems->toArray();
    }

    /**
     * Set isMegamenu
     *
     * @param boolean $isMegamenu
     *
     * @return MenuInterface
     */
    public function setIsMegamenu(bool $isMegamenu): self
    {
        $this->isMegamenu = $isMegamenu;

        return $this;
    }

    /**
     * Get isMegamenu
     *
     * @return null|boolean
     */
    public function getIsMegamenu(): ?bool
    {
        return $this->isMegamenu;
    }



    public function __toString(): string
    {
        return isset($this->name) ? $this->name : "";
    }
}
