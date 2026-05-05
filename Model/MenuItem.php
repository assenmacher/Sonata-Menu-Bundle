<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Model\PageInterface;
use App\Sonata\PageBundle\Model\PageInterface as AppSonataPageInterface;
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
     * @var AppSonataPageInterface|null

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
     * @ORM\Column(name="max_columns", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $maxColumns;

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
     * @var string|null
     *
     * @ORM\Column(name="icon_class", type="string", length=255, nullable=true)
     */
    protected $iconClass;

    /**
     * @var string|null
     *
     * @ORM\Column(name="block_alias", type="string", length=255, nullable=true)
     */
    protected $blockAlias;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_overview_page", type="boolean", nullable=true, options={"default":false})
     */
    protected $isOverviewPage;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="hide_in_slm", type="boolean", nullable=true, options={"default":false})
     */
    protected $hideInSlm;

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
     *
     * @return MenuItemInterface
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
     * Set title
     *
     * @param string $name
     *
     * @return MenuItemInterface
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * Set info text
     *
     * @param string|null $infoText
     *
     * @return MenuItemInterface
     */
    public function setInfoText(?string $infoText): self
    {
        $this->infoText = $infoText;

        return $this;
    }

    /**
     * Get info text
     *
     * @return null|string
     */
    public function getInfoText(): ?string
    {
        return $this->infoText;
    }

    /**
     * Has info text
     *
     * @return bool
     */
    public function hasInfoText(): bool
    {
        return is_string($this->getInfoText()) && $this->getInfoText() !== '';
    }

    /**
     * Set url
     *
     * @param null|string $url
     *
     * @return MenuItemInterface
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Set attributeClass
     *
     * @param null|string $attributeClass
     *
     * @return MenuItemInterface
     */
    public function setAttributeClass(?string $attributeClass): self
    {
        $this->attributeClass = $attributeClass;

        return $this;
    }

    /**
     * Get attributeClass
     *
     * @return null|string
     */
    public function getAttributeClass(): ?string
    {
        return $this->attributeClass;
    }

    /**
     * Set attributeStyle
     *
     * @param null|string $attributeStyle
     *
     * @return MenuItemInterface
     */
    public function setAttributeStyle(?string $attributeStyle): self
    {
        $this->attributeStyle = $attributeStyle;

        return $this;
    }

    /**
     * Get attributeStyle
     *
     * @return null|string
     */
    public function getAttributeStyle(): ?string
    {
        return $this->attributeStyle;
    }

    /**
     * Set attributeId
     *
     * @param null|string $attributeId
     *
     * @return MenuItemInterface
     */
    public function setAttributeId(?string $attributeId): self
    {
        $this->attributeId = $attributeId;

        return $this;
    }

    /**
     * Get attributeId
     *
     * @return null|string
     */
    public function getAttributeId(): ?string
    {
        return $this->attributeId;
    }

    /**
     * Set linkAttributeClass
     *
     * @param null|string $linkAttributeClass
     *
     * @return MenuItemInterface
     */
    public function setLinkAttributeClass(?string $linkAttributeClass): self
    {
        $this->linkAttributeClass = $linkAttributeClass;

        return $this;
    }

    /**
     * Get linkAttributeClass
     *
     * @return null|string
     */
    public function getLinkAttributeClass(): ?string
    {
        return $this->linkAttributeClass;
    }

    /**
     * Set linkAttributeStyle
     *
     * @param null|string $linkAttributeStyle
     *
     * @return MenuItemInterface
     */
    public function setLinkAttributeStyle(?string $linkAttributeStyle): self
    {
        $this->linkAttributeStyle = $linkAttributeStyle;

        return $this;
    }

    /**
     * Get linkAttributeStyle
     *
     * @return null|string
     */
    public function getLinkAttributeStyle(): ?string
    {
        return $this->linkAttributeStyle;
    }

    /**
     * Set linkAttributeId
     *
     * @param null|string $linkAttributeId
     *
     * @return MenuItemInterface
     */
    public function setLinkAttributeId(?string $linkAttributeId): self
    {
        $this->linkAttributeId = $linkAttributeId;

        return $this;
    }

    /**
     * Get linkAttributeId
     *
     * @return null|string
     */
    public function getLinkAttributeId(): ?string
    {
        return $this->linkAttributeId;
    }

    /**
     * Set labelAttributeClass
     *
     * @param null|string $labelAttributeClass
     *
     * @return MenuItemInterface
     */
    public function setLabelAttributeClass(?string $labelAttributeClass): self
    {
        $this->labelAttributeClass = $labelAttributeClass;

        return $this;
    }

    /**
     * Get labelAttributeClass
     *
     * @return null|string
     */
    public function getLabelAttributeClass(): ?string
    {
        return $this->labelAttributeClass;
    }

    /**
     * Set labelAttributeStyle
     *
     * @param null|string $labelAttributeStyle
     *
     * @return MenuItemInterface
     */
    public function setLabelAttributeStyle(?string $labelAttributeStyle): self
    {
        $this->labelAttributeStyle = $labelAttributeStyle;

        return $this;
    }

    /**
     * Get labelAttributeStyle
     *
     * @return null|string
     */
    public function getLabelAttributeStyle(): ?string
    {
        return $this->labelAttributeStyle;
    }

    /**
     * Set labelAttributeId
     *
     * @param null|string $labelAttributeId
     *
     * @return MenuItemInterface
     */
    public function setLabelAttributeId(?string $labelAttributeId): self
    {
        $this->labelAttributeId = $labelAttributeId;

        return $this;
    }

    /**
     * Get labelAttributeId
     *
     * @return null|string
     */
    public function getLabelAttributeId(): ?string
    {
        return $this->labelAttributeId;
    }

    /**
     * Set position
     *
     * @param int $position
     *
     * @return MenuItemInterface
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int 
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Set target
     *
     * @param null|bool $target
     *
     * @return MenuItemInterface
     */
    public function setTarget(?bool $target): self
    {
        $this->target = (bool) $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return null|bool
     */
    public function getTarget(): ?bool
    {
        return $this->target;
    }

    /**
     * Set enabled
     *
     * @param null|bool $enabled
     *
     * @return MenuItemInterface
     */
    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * Set locale enabled
     *
     * @param bool $enabled
     *
     * @return MenuItemInterface
     */
    public function setLocaleEnabled(?bool $localeEnabled): self
    {
        $this->localeEnabled = $localeEnabled;

        return $this;
    }

    /**
     * Get locale enabled
     *
     * @return boolean
     */
    public function getLocaleEnabled(): ?bool
    {
        return $this->localeEnabled;
    }

    /**
     * Get page
     *
     * @return null|AppSonataPageInterface
     */
    public function getPage(): ?AppSonataPageInterface
    {
        return $this->page;
    }

    /**
     * Set page
     *
     * @param null|AppSonataPageInterface $page
     *
     * @return MenuItemInterface
     */
    public function setPage(?AppSonataPageInterface $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set page parameter
     *
     * @param null|string $pageParameter
     *
     * @return MenuItemInterface
     */
    public function setPageParameter(?string $pageParameter): self
    {
        $this->pageParameter = $pageParameter;

        return $this;
    }

    /**
     * Get page parameter
     *
     * @return null|string
     */
    public function getPageParameter(): ?string
    {
        return $this->pageParameter;
    }

    /**
     * Set page anchor
     *
     * @param null|string $pageAnchor
     *
     * @return MenuItemInterface
     */
    public function setPageAnchor(?string $pageAnchor): self
    {
        $this->pageAnchor = $pageAnchor;

        return $this;
    }

    /**
     * Get page anchor
     *
     * @return null|string
     */
    public function getPageAnchor(): ?string
    {
        return $this->pageAnchor;
    }

    /**
     * Get parent
     *
     * @return null|MenuItemInterface
     */
    public function getParent(): ?MenuItemInterface
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param null|MenuItemInterface $parent
     *
     * @return MenuItemInterface
     */
    public function setParent(?MenuItemInterface $parent): self
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
     * @return MenuItemInterface
     */
    public function addChild(MenuItemInterface $child): self
    {
        $this->children->add($child);

        return $this;
    }

    /**
     * Remove child
     *
     * @param MenuItemInterface $child
     *
     * @return MenuItemInterface
     */
    public function removeChild(MenuItemInterface $child): self
    {
        $this->children->removeElement($child);

        return $this;
    }

    /**
     * Set children
     *
     * @param ArrayCollection $children
     *
     * @return MenuItemInterface
     */
    public function setChildren(ArrayCollection $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    /**
     * Set menu
     *
     * @param null|MenuInterface $menu
     *
     * @return MenuItemInterface
     */
    public function setMenu(?MenuInterface $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return null|MenuInterface
     */
    public function getMenu(): ?MenuInterface
    {
        return $this->menu;
    }

    /**
     * Has child
     *
     * @return bool
     */
    public function hasChild(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * Has parent
     *
     * @return bool
     */
    public function hasParent(): bool
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

     * @return MenuItemInterface
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level indented name.
     *
     * @param string $indentedWith
     *
     * @return string
     */
    public function getLevelIndentedName(string $indentedWith = '--'): string
    {
        $name = (string) $this->getName() !== '' ? $this->getName() : '[new]';

        return str_pad('', (strlen($indentedWith) * $this->getLevel()), $indentedWith) . ' ' . $name;
    }

    /**
     * @return null|int
     */
    public function getImageId(): ?int
    {
        return $this->imageId;
    }

    /**
     * @param int $imageId
     *
     * @return MenuItemInterface
     */
    public function setImageId(?int $imageId): self
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * @return MediaInterface
     */
    public function getImage(): ?MediaInterface
    {
        return $this->image;
    }

    /**
     * @param null|MediaInterface $image
     *
     * @return MenuItemInterface
     */
    public function setImage(?MediaInterface $image): self
    {
        $this->image = $image;

        return $this;
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
     *
     * @return MenuItemInterface
     */
    public function setArticleTags(?array $articleTags): self
    {
        $this->articleTags = $articleTags;

        return $this;
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
     *
     * @return MenuItemInterface
     */
    public function setArticleCounter(?int $articleCounter) :self
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
     *
     * @return MenuItemInterface
     */
    public function setColumns(?int $columns) :self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * get maxColumns
     *
     * @param null|int $default
     * *
     * @return null|int
     */
    public function getMaxColumns(?int $default = null): ?int
    {
        return $this->maxColumns ?: $default;
    }

    /**
     * set maxColumns
     *
     * @param null|int $maxColumns
     *
     * @return MenuItemInterface
     */
    public function setMaxColumns(?int $maxColumns) :self
    {
        $this->maxColumns = $maxColumns;

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
     *
     * @return MenuItemInterface
     */
    public function setColumn(?int $column) :self
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Set hasOverlines
     *
     * @param null|boolean $hasOverlines
     *
     * @return MenuItemInterface
     */
    public function setHasOverlines(?bool $hasOverlines) :self
    {
        $this->hasOverlines = (bool) $hasOverlines;

        return $this;
    }

    /**
     * Get hasOverlines
     *
     * @return null|boolean
     */
    public function getHasOverlines(): ?bool
    {
        return $this->hasOverlines;
    }

    /**
     * Set hasGroups
     *
     * @param boolean $hasGroups
     *
     * @return MenuItemInterface
     */
    public function setHasGroups(?bool $hasGroups) :self
    {
        $this->hasGroups = (bool) $hasGroups;

        return $this;
    }

    /**
     * Get hasGroups
     *
     * @return null|boolean
     */
    public function getHasGroups(): ?bool
    {
        return $this->hasGroups;
    }

    /**
     * Set icon class
     *
     * @param null|string $iconClass
     *
     * @return MenuItemInterface
     */
    public function setIconClass(?string $iconClass): self
    {
        $this->iconClass = $iconClass;

        return $this;
    }

    /**
     * Get icon class
     *
     * @return null|string
     */
    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    /**
     * Has icon class
     *
     * @return bool
     */
    public function hasIconClass(): bool
    {
        return is_string($this->getIconClass()) && $this->getIconClass() !== '';
    }

    /**
     * Set block alias
     *
     * @param null|string $blockAlias
     *
     * @return MenuItemInterface
     */
    public function setBlockAlias(?string $blockAlias): self
    {
        $this->blockAlias = $blockAlias;

        return $this;
    }

    /**
     * Get block alias
     *
     * @return null|string
     */
    public function getBlockAlias(): ?string
    {
        return $this->blockAlias;
    }

    /**
     * Has block class
     *
     * @return bool
     */
    public function hasBlockAlias(): bool
    {
        return is_string($this->getBlockAlias()) && $this->getBlockAlias() !== '';
    }

    /**
     * Set isOverviewPage
     *
     * @param null|boolean $isOverviewPage
     *
     * @return MenuItemInterface
     */
    public function setIsOverviewPage(?bool $isOverviewPage): self
    {
        $this->isOverviewPage = (bool) $isOverviewPage;

        return $this;
    }

    /**
     * Get isOverviewPage
     *
     * @return boolean
     */
    public function getIsOverviewPage(): ?bool
    {
        return $this->isOverviewPage;
    }

    /**
     * Set hideInSlm (hide in second level menu)
     *
     * @param null|boolean $hideInSlm
     *
     * @return MenuItemInterface
     */
    public function setHideInSlm(?bool $hideInSlm): self
    {
        $this->hideInSlm = (bool) $hideInSlm;

        return $this;
    }

    /**
     * Get hideInSlm (hide in second level menu)
     *
     * @return null|boolean
     */
    public function getHideInSlm(): ?bool
    {
        return $this->hideInSlm;
    }

    public function __toString(): string
    {
        return isset($this->name) ? $this->name : "";
    }

}
