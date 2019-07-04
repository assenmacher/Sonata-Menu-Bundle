<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;

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
     * @var \Prodigious\Sonata\MenuBundle\Model\SiteInterface

     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\SiteInterface")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $site;

    /**
     * @ORM\OneToMany(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface", mappedBy="menu", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $menuItems;

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
     * @return Menu
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Menu
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Get site
     *
     * @return null|\Prodigious\Sonata\MenuBundle\Model\SiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return MenuItem
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        if(!$enabled && $this->hasChild()) {
            foreach ($this->children as $child) {
                if($child->enabled) {
                    $child->setEnabled(false);
                    $child->setParent(null);
                }
            }
            $this->children = new ArrayCollection();
        }

        return $this;
    }

    /**
     * Get locale enabled
     *
     * @return boolean
     */
    public function getLocaleEnabled()
    {
        return $this->localeEnabled;
    }


    /**
     * Set locale enabled
     *
     * @param boolean $enabled
     * @return Menu
     */
    public function setLocaleEnabled($localeEnabled)
    {
        $this->localeEnabled = $localeEnabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set site
     *
     * @param null|\Prodigious\Sonata\MenuBundle\Model\SiteInterface $site
     *
     * @return Menu
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Add menuItem
     *
     * @param \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $menuItem
     *
     * @return Menu
     */
    public function addMenuItem(\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $menuItem)
    {
        $this->menuItems->add($menuItem);

        $menuItem->setMenu($this);

        return $this;
    }

    /**
     * Remove menuItem
     *
     * @param \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $menuItem
     */
    public function removeMenuItem(\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $menuItem)
    {
        $this->menuItems->removeElement($menuItem);
    }

    /**
     * Set menuItems
     *
     * @param ArrayCollection $menuItems
     *
     * @return Menu
     */
    public function setMenuItems(ArrayCollection $menuItems)
    {
        $this->menuItems = $menuItems;

        return $this;
    }

    /**
     * Get menuItems
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMenuItems()
    {   
        return $this->menuItems->toArray();
    }

    public function __toString()
    {
        return isset($this->name) ? $this->name : "";
    }
}
