<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;

/**
 * MenuItem
 *
 * @ORM\Table(name="sonata_menu_item")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
abstract class MenuItem implements MenuItemInterface
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Translatable
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="class_attribute", type="string", length=255, nullable=true)
     */
    protected $classAttribute;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="target", type="boolean", nullable=true, options={"default":false})
     */
    protected $target;

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
     * @var \Prodigious\Sonata\MenuBundle\Model\PageInterface

     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\PageInterface")
     * @ORM\JoinColumn(name="page", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $page;

    /**
     * @var string
     *
     * @ORM\Column(name="page_parameter", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $pageParameter;

    /**
     * @var \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface
     *
     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface", inversedBy="children", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface", mappedBy="parent", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuInterface", inversedBy="menuItems")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $menu;

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->position = 999;
        $this->enabled = true;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MenuItem
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
     * Set title
     *
     * @param string $name
     * @return MenuItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return MenuItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set classAttribute
     *
     * @param string $classAttribute
     * @return MenuItem
     */
    public function setClassAttribute($classAttribute)
    {
        $this->classAttribute = $classAttribute;

        return $this;
    }

    /**
     * Get classAttribute
     *
     * @return string 
     */
    public function getClassAttribute()
    {
        return $this->classAttribute;
    }

    /**
     * Set position
     *
     * @param int $position
     * @return MenuItem
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set target
     *
     * @param boolean $enabled
     * @return MenuItem
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return boolean 
     */
    public function getTarget()
    {
        return $this->target;
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
     * @return MenuItem
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
     * Get page
     *
     * @return null|\Prodigious\Sonata\MenuBundle\Model\PageInterface
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     *
     * @param null|\Prodigious\Sonata\MenuBundle\Model\PageInterface $page
     *
     * @return MenuItem
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set page parameter
     *
     * @param string $pageParameter
     * @return MenuItem
     */
    public function setPageParameter($pageParameter)
    {
        $this->pageParameter = $pageParameter;

        return $this;
    }

    /**
     * Get page parameter
     *
     * @return string
     */
    public function getPageParameter()
    {
        return $this->pageParameter;
    }

    /**
     * Get parent
     *
     * @return \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $parent
     *
     * @return MenuItem
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        
        if(!is_null($parent))
            $parent->addChild($this);

        return $this;
    }

    /**
     * Add child
     *
     * @param \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $child
     *
     * @return $this
     */
    public function addChild(\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $child)
    {
        $this->children->add($child);

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $child
     */
    public function removeChild(\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Set children
     *
     * @param ArrayCollection $children
     *
     * @return MenuItem
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children->toArray();
    }

    /**
     * Set menu
     *
     * @param \Prodigious\Sonata\MenuBundle\Model\MenuInterface $menu
     *
     * @return MenuItem
     */
    public function setMenu(\Prodigious\Sonata\MenuBundle\Model\MenuInterface $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Prodigious\Sonata\MenuBundle\Model\MenuInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Has child
     */
    public function hasChild()
    {
        return count($this->children) > 0;
    }

    /**
     * Has parent
     */
    public function hasParent()
    {
        return !is_null($this->parent);
    }

    public function getActiveChildren()
    {
        $children = array();

        foreach ($this->children as $child) {
            if($child->enabled) {
                array_push($children, $child);
            }
        }

        return $children;
    }

    /**
     * get level
     *
     * @return int
     */
    public function getLevel() :int
    {
        return $this->level;
    }

    /**
     * set level
     *
     * @param int $level

     * @return Page
     */
    public function setLevel(int $level) :MenuItem
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level indented name.
     *
     * @return string levelIndentedName
     */
    public function getLevelIndentedName($indentedWith = '--') :string
    {
        return str_pad('', (strlen($indentedWith) * $this->getLevel()), $indentedWith) . ' ' . $this->getName();
    }

    public function __toString()
    {
        return isset($this->name) ? $this->name : "";
    }

}
