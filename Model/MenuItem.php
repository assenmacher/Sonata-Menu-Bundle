<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Model\PageInterface;
use Sonata\MediaBundle\Model\MediaInterface;

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
     * @var string|null
     *
     * @ORM\Column(name="info_text", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $infoText;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attribute_class", type="string", length=255, nullable=true)
     */
    protected $attributeClass;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attribute_style", type="string", length=255, nullable=true)
     */
    protected $attributeStyle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attribute_id", type="string", length=255, nullable=true)
     */
    protected $attributeId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link_attribute_class", type="string", length=255, nullable=true)
     */
    protected $linkAttributeClass;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link_attribute_style", type="string", length=255, nullable=true)
     */
    protected $linkAttributeStyle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link_attribute_id", type="string", length=255, nullable=true)
     */
    protected $linkAttributeId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="label_attribute_class", type="string", length=255, nullable=true)
     */
    protected $labelAttributeClass;

    /**
     * @var string|null
     *
     * @ORM\Column(name="label_attribute_style", type="string", length=255, nullable=true)
     */
    protected $labelAttributeStyle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="label_attribute_id", type="string", length=255, nullable=true)
     */
    protected $labelAttributeId;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="position", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $position;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="target", type="boolean", nullable=true, options={"default":false})
     */
    protected $target;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true, options={"default":true})
     */
    protected $enabled;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="locale_enabled", type="boolean", nullable=true, options={"default":true})
     * @Gedmo\Translatable
     */
    protected $localeEnabled;

    /**
     * @var PageInterface|null

     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\PageInterface")
     * @ORM\JoinColumn(name="page", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $page;

    /**
     * @var string|null
     *
     * @ORM\Column(name="page_parameter", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $pageParameter;

    /**
     * @var string|null
     *
     * @ORM\Column(name="page_anchor", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $pageAnchor;

    /**
     * @var MenuItemInterface|null
     *
     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuItemInterface", mappedBy="parent", cascade={"remove", "persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @var MenuInterface
     *
     * @ORM\ManyToOne(targetEntity="\Prodigious\Sonata\MenuBundle\Model\MenuInterface", inversedBy="menuItems")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id", nullable=false)
     */
    protected $menu;

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * @var int|null
     *
     * @ORM\Column(name="image_id", type="integer", nullable=true)
     */
    protected $imageId;
    protected $image;

    /**
     * @var array|null
     *
     * @ORM\Column(name="article_tags", type="array", nullable=true)
     */
    protected $articleTags;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="article_count", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $articleCounter;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="spalten", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $columns;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="spalte", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $column;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="has_overlines", type="boolean", nullable=true, options={"default":false})
     */
    protected $hasOverlines;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="has_groups", type="boolean", nullable=true, options={"default":false})
     */
    protected $hasGroups;

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
     * Set info text
     *
     * @param string|null $infoText
     * @return MenuItem
     */
    public function setInfoText(?string $infoText)
    {
        $this->infoText = $infoText;

        return $this;
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getInfoText(): ?string
    {
        return $this->infoText;
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
     * Set attributeClass
     *
     * @param string $attributeClass
     * @return MenuItem
     */
    public function setAttributeClass($attributeClass)
    {
        $this->attributeClass = $attributeClass;

        return $this;
    }

    /**
     * Get attributeClass
     *
     * @return string
     */
    public function getAttributeClass()
    {
        return $this->attributeClass;
    }

    /**
     * Set attributeStyle
     *
     * @param string $attributeStyle
     * @return MenuItem
     */
    public function setAttributeStyle($attributeStyle)
    {
        $this->attributeStyle = $attributeStyle;

        return $this;
    }

    /**
     * Get attributeStyle
     *
     * @return string
     */
    public function getAttributeStyle()
    {
        return $this->attributeStyle;
    }

    /**
     * Set attributeId
     *
     * @param string $attributeId
     * @return MenuItem
     */
    public function setAttributeId($attributeId)
    {
        $this->attributeId = $attributeId;

        return $this;
    }

    /**
     * Get attributeId
     *
     * @return string
     */
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    /**
     * Set linkAttributeClass
     *
     * @param string $linkAttributeClass
     * @return MenuItem
     */
    public function setLinkAttributeClass($linkAttributeClass)
    {
        $this->linkAttributeClass = $linkAttributeClass;

        return $this;
    }

    /**
     * Get linkAttributeClass
     *
     * @return string
     */
    public function getLinkAttributeClass()
    {
        return $this->linkAttributeClass;
    }

    /**
     * Set linkAttributeStyle
     *
     * @param string $linkAttributeStyle
     * @return MenuItem
     */
    public function setLinkAttributeStyle($linkAttributeStyle)
    {
        $this->linkAttributeStyle = $linkAttributeStyle;

        return $this;
    }

    /**
     * Get linkAttributeStyle
     *
     * @return string
     */
    public function getLinkAttributeStyle()
    {
        return $this->linkAttributeStyle;
    }

    /**
     * Set linkAttributeId
     *
     * @param string $linkAttributeId
     * @return MenuItem
     */
    public function setLinkAttributeId($linkAttributeId)
    {
        $this->linkAttributeId = $linkAttributeId;

        return $this;
    }

    /**
     * Get linkAttributeId
     *
     * @return string
     */
    public function getLinkAttributeId()
    {
        return $this->linkAttributeId;
    }

    /**
     * Set labelAttributeClass
     *
     * @param string $labelAttributeClass
     * @return MenuItem
     */
    public function setLabelAttributeClass($labelAttributeClass)
    {
        $this->labelAttributeClass = $labelAttributeClass;

        return $this;
    }

    /**
     * Get labelAttributeClass
     *
     * @return string
     */
    public function getLabelAttributeClass()
    {
        return $this->labelAttributeClass;
    }

    /**
     * Set labelAttributeStyle
     *
     * @param string $labelAttributeStyle
     * @return MenuItem
     */
    public function setLabelAttributeStyle($labelAttributeStyle)
    {
        $this->labelAttributeStyle = $labelAttributeStyle;

        return $this;
    }

    /**
     * Get labelAttributeStyle
     *
     * @return string
     */
    public function getLabelAttributeStyle()
    {
        return $this->labelAttributeStyle;
    }

    /**
     * Set labelAttributeId
     *
     * @param string $labelAttributeId
     * @return MenuItem
     */
    public function setLabelAttributeId($labelAttributeId)
    {
        $this->labelAttributeId = $labelAttributeId;

        return $this;
    }

    /**
     * Get labelAttributeId
     *
     * @return string
     */
    public function getLabelAttributeId()
    {
        return $this->labelAttributeId;
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
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
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
     * Get locale enabled
     *
     * @return boolean
     */
    public function getLocaleEnabled()
    {
        return $this->localeEnabled;
    }

    /**
     * Get page
     *
     * @return null|PageInterface
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     *
     * @param null|PageInterface $page
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
     * Set page anchor
     *
     * @param string $pageAnchor
     * @return MenuItem
     */
    public function setPageAnchor($pageAnchor)
    {
        $this->pageAnchor = $pageAnchor;

        return $this;
    }

    /**
     * Get page anchor
     *
     * @return string
     */
    public function getPageAnchor()
    {
        return $this->pageAnchor;
    }

    /**
     * Get parent
     *
     * @return null|MenuItemInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param null|MenuItemInterface $parent
     *
     * @return null|MenuItemInterface
     */
    public function setParent(?MenuItemInterface $parent)
    {
        $this->parent = $parent;
        
        if(!is_null($parent))
            $parent->addChild($this);

        return $this;
    }

    /**
     * Add child
     *
     * @param MenuItemInterface $child
     *
     * @return $this
     */
    public function addChild(MenuItemInterface $child)
    {
        $this->children->add($child);

        return $this;
    }

    /**
     * Remove child
     *
     * @param MenuItemInterface $child
     */
    public function removeChild(MenuItemInterface $child)
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
     * @return array
     */
    public function getChildren()
    {
        return $this->children->toArray();
    }

    /**
     * Set menu
     *
     * @param MenuInterface $menu
     *
     * @return MenuItem
     */
    public function setMenu(?MenuInterface $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return MenuInterface
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

    /**
     * get level
     *
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * set level
     *
     * @param int $level

     * @return Page
     */
    public function setLevel(int $level): MenuItem
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level indented name.
     *
     * @param string $indentedWith
     *
     * @return string levelIndentedName
     */
    public function getLevelIndentedName(string $indentedWith = '--'): string
    {
        return str_pad('', (strlen($indentedWith) * $this->getLevel()), $indentedWith) . ' ' . $this->getName();
    }

    /**
     * @return int
     */
    public function getImageId(): ?int
    {
        return $this->imageId;
    }

    /**
     * @param int $imageId
     */
    public function setImageId(?int $imageId): void
    {
        $this->imageId = $imageId;
    }

    /**
     * @return MediaInterface
     */
    public function getImage(): ?MediaInterface
    {
        return $this->image;
    }

    /**
     * @param MediaInterface $image
     */
    public function setImage(?MediaInterface $image): void
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function hasImage(): bool
    {
        return !empty($this->getImage());
    }

    /**
     * @return array|null
     */
    public function getArticleTags(): ?array
    {
        return $this->articleTags;
    }

    /**
     * @param array|null $articleTags
     */
    public function setArticleTags(?array $articleTags): void
    {
        $this->articleTags = $articleTags;
    }

    /**
     * @return bool
     */
    public function hasArticleTags(): bool
    {
        return !empty($this->getArticleTags());
    }

    /**
     * get article counter
     *
     * @return null|int
     */
    public function getArticleCounter(): ?int
    {
        return $this->articleCounter;
    }

    /**
     * set article counter
     *
     * @param null|int $articleCounter

     * @return MenuItem
     */
    public function setArticleCounter(?int $articleCounter) :MenuItem
    {
        $this->articleCounter = $articleCounter;

        return $this;
    }

    /**
     * get columns
     *
     * @return null|int
     */
    public function getColumns(): ?int
    {
        return $this->columns;
    }

    /**
     * set columns
     *
     * @param null|int $columns

     * @return MenuItem
     */
    public function setColumns(?int $columns) :MenuItem
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * get column
     *
     * @return null|int
     */
    public function getColumn(): ?int
    {
        return $this->column;
    }

    /**
     * set column
     *
     * @param null|int $column

     * @return MenuItem
     */
    public function setColumn(?int $column) :MenuItem
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Set hasOverlines
     *
     * @param boolean $hasOverlines
     * @return MenuItem
     */
    public function setHasOverlines($hasOverlines)
    {
        $this->hasOverlines = $hasOverlines;

        return $this;
    }

    /**
     * Get hasOverlines
     *
     * @return boolean
     */
    public function getHasOverlines()
    {
        return $this->hasOverlines;
    }

    /**
     * Set hasGroups
     *
     * @param boolean $hasGroups
     * @return MenuItem
     */
    public function setHasGroups($hasGroups)
    {
        $this->hasGroups = $hasGroups;

        return $this;
    }

    /**
     * Get hasGroups
     *
     * @return boolean
     */
    public function getHasGroups()
    {
        return $this->hasGroups;
    }

    public function __toString()
    {
        return isset($this->name) ? $this->name : "";
    }

}
