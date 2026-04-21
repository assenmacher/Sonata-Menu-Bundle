<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use App\Sonata\PageBundle\Model\PageInterface as AppSonataPageInterface;
use Sonata\MediaBundle\Model\MediaInterface;

interface MenuItemInterface
{

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MenuItemInterface
     */
    public function setName(string $name): self;

    /**
     * Get name
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set title
     *
     * @param string $name
     *
     * @return MenuItemInterface
     */
    public function setTitle(string $title): self;

    /**
     * Get title
     *
     * @return null|string
     */
    public function getTitle(): ?string;


    /**
     * Set info text
     *
     * @param string|null $infoText
     *
     * @return MenuItemInterface
     */
    public function setInfoText(?string $infoText): self;

    /**
     * Get info text
     *
     * @return null|string
     */
    public function getInfoText(): ?string;

    /**
     * Has info text
     *
     * @return bool
     */
    public function hasInfoText(): bool;

    /**
     * Set url
     *
     * @param null|string $url
     *
     * @return MenuItemInterface
     */
    public function setUrl(?string $url): self;

    /**
     * Get url
     *
     * @return null|string
     */
    public function getUrl(): ?string;

    /**
     * Set attributeClass
     *
     * @param null|string $attributeClass
     *
     * @return MenuItemInterface
     */
    public function setAttributeClass(?string $attributeClass): self;

    /**
     * Get attributeClass
     *
     * @return null|string
     */
    public function getAttributeClass(): ?string;

    /**
     * Set attributeStyle
     *
     * @param null|string $attributeStyle
     *
     * @return MenuItemInterface
     */
    public function setAttributeStyle(?string $attributeStyle): self;

    /**
     * Get attributeStyle
     *
     * @return null|string
     */
    public function getAttributeStyle(): ?string;

    /**
     * Set attributeId
     *
     * @param null|string $attributeId
     *
     * @return MenuItemInterface
     */
    public function setAttributeId(?string $attributeId): self;

    /**
     * Get attributeId
     *
     * @return null|string
     */
    public function getAttributeId(): ?string;

    /**
     * Set linkAttributeClass
     *
     * @param null|string $linkAttributeClass
     *
     * @return MenuItemInterface
     */
    public function setLinkAttributeClass(?string $linkAttributeClass): self;

    /**
     * Get linkAttributeClass
     *
     * @return null|string
     */
    public function getLinkAttributeClass(): ?string;

    /**
     * Set linkAttributeStyle
     *
     * @param null|string $linkAttributeStyle
     *
     * @return MenuItemInterface
     */
    public function setLinkAttributeStyle(?string $linkAttributeStyle): self;

    /**
     * Get linkAttributeStyle
     *
     * @return null|string
     */
    public function getLinkAttributeStyle(): ?string;

    /**
     * Set linkAttributeId
     *
     * @param null|string $linkAttributeId
     *
     * @return MenuItemInterface
     */
    public function setLinkAttributeId(?string $linkAttributeId): self;

    /**
     * Get linkAttributeId
     *
     * @return null|string
     */
    public function getLinkAttributeId(): ?string;

    /**
     * Set labelAttributeClass
     *
     * @param null|string $labelAttributeClass
     *
     * @return MenuItemInterface
     */
    public function setLabelAttributeClass(?string $labelAttributeClass): self;

    /**
     * Get labelAttributeClass
     *
     * @return null|string
     */
    public function getLabelAttributeClass(): ?string;

    /**
     * Set labelAttributeStyle
     *
     * @param null|string $labelAttributeStyle
     *
     * @return MenuItemInterface
     */
    public function setLabelAttributeStyle(?string $labelAttributeStyle): self;

    /**
     * Get labelAttributeStyle
     *
     * @return null|string
     */
    public function getLabelAttributeStyle(): ?string;

    /**
     * Set labelAttributeId
     *
     * @param null|string $labelAttributeId
     *
     * @return MenuItemInterface
     */
    public function setLabelAttributeId(?string $labelAttributeId): self;

    /**
     * Get labelAttributeId
     *
     * @return null|string
     */
    public function getLabelAttributeId(): ?string;

    /**
     * Set position
     *
     * @param int $position
     * @return MenuItemInterface
     */
    public function setPosition(int $position): self;

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition(): int;

    /**
     * Set target
     *
     * @param null|bool $target
     *
     * @return MenuItemInterface
     */
    public function setTarget(?bool $target): self;

    /**
     * Get target
     *
     * @return null|bool
     */
    public function getTarget(): ?bool;

    /**
     * Set enabled
     *
     * @param null|bool $enabled
     *
     * @return MenuItemInterface
     */
    public function setEnabled(?bool $enabled): self;

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled(): ?bool;

    /**
     * Set locale enabled
     *
     * @param bool $enabled
     *
     * @return MenuItemInterface
     */
    public function setLocaleEnabled(?bool $localeEnabled): self;

    /**
     * Get locale enabled
     *
     * @return boolean
     */
    public function getLocaleEnabled(): ?bool;

    /**
     * Get page
     *
     * @return null|AppSonataPageInterface
     */
    public function getPage(): ?AppSonataPageInterface;

    /**
     * Set page
     *
     * @param null|AppSonataPageInterface $page
     *
     * @return MenuItemInterface
     */
    public function setPage(?AppSonataPageInterface $page): self;

    /**
     * Set page parameter
     *
     * @param null|string $pageParameter
     *
     * @return MenuItemInterface
     */
    public function setPageParameter(?string $pageParameter): self;

    /**
     * Get page parameter
     *
     * @return null|string
     */
    public function getPageParameter(): ?string;

    /**
     * Set page anchor
     *
     * @param null|string $pageAnchor
     *
     * @return MenuItemInterface
     */
    public function setPageAnchor(?string $pageAnchor): self;

    /**
     * Get page anchor
     *
     * @return null|string
     */
    public function getPageAnchor(): ?string;

    /**
     * Get parent
     *
     * @return null|MenuItemInterface
     */
    public function getParent(): ?MenuItemInterface;

    /**
     * Set parent
     *
     * @param null|MenuItemInterface $parent
     *
     * @return MenuItemInterface
     */
    public function setParent(?MenuItemInterface $parent): self;

    /**
     * Add child
     *
     * @param MenuItemInterface $child
     *
     * @return MenuItemInterface
     */
    public function addChild(MenuItemInterface $child): self;

    /**
     * Remove child
     *
     * @param MenuItemInterface $child
     *
     * @return MenuItemInterface
     */
    public function removeChild(MenuItemInterface $child): self;

    /**
     * Set children
     *
     * @param ArrayCollection $children
     *
     * @return MenuItemInterface
     */
    public function setChildren(ArrayCollection $children): self;

    /**
     * Get children
     *
     * @return array
     */
    public function getChildren(): array;

    /**
     * Set menu
     *
     * @param null|MenuInterface $menu
     *
     * @return MenuItemInterface
     */
    public function setMenu(?MenuInterface $menu): self;

    /**
     * Get menu
     *
     * @return null|MenuInterface
     */
    public function getMenu(): ?MenuInterface;

    /**
     * Has child
     *
     * @return bool
     */
    public function hasChild(): bool;

    /**
     * Has parent
     *
     * @return bool
     */
    public function hasParent(): bool;

    /**
     * get level
     *
     * @return int
     */
    public function getLevel(): int;

    /**
     * set level
     *
     * @param int $level

     * @return MenuItemInterface
     */
    public function setLevel(int $level): self;

    /**
     * Get level indented name.
     *
     * @param string $indentedWith
     *
     * @return string
     */
    public function getLevelIndentedName(string $indentedWith = '--'): string;

    /**
     * @return null|int
     */
    public function getImageId(): ?int;

    /**
     * @param int $imageId
     *
     * @return MenuItemInterface
     */
    public function setImageId(?int $imageId): self;

    /**
     * @return MediaInterface
     */
    public function getImage(): ?MediaInterface;

    /**
     * @param null|MediaInterface $image
     *
     * @return MenuItemInterface
     */
    public function setImage(?MediaInterface $image): self;

    /**
     * @return bool
     */
    public function hasImage(): bool;

    /**
     * @return array|null
     */
    public function getArticleTags(): ?array;

    /**
     * @param array|null $articleTags
     *
     * @return MenuItemInterface
     */
    public function setArticleTags(?array $articleTags): self;

    /**
     * @return bool
     */
    public function hasArticleTags(): bool;

    /**
     * get article counter
     *
     * @return null|int
     */
    public function getArticleCounter(): ?int;

    /**
     * set article counter
     *
     * @param null|int $articleCounter
     *
     * @return MenuItemInterface
     */
    public function setArticleCounter(?int $articleCounter) :self;

    /**
     * get columns
     *
     * @return null|int
     */
    public function getColumns(): ?int;

    /**
     * set columns
     *
     * @param null|int $columns
     *
     * @return MenuItemInterface
     */
    public function setColumns(?int $columns) :self;

    /**
     * get column
     *
     * @return null|int
     */
    public function getColumn(): ?int;

    /**
     * set column
     *
     * @param null|int $column
     *
     * @return MenuItemInterface
     */
    public function setColumn(?int $column) :self;

    /**
     * Set hasOverlines
     *
     * @param null|boolean $hasOverlines
     *
     * @return MenuItemInterface
     */
    public function setHasOverlines(?bool $hasOverlines) :self;

    /**
     * Get hasOverlines
     *
     * @return null|boolean
     */
    public function getHasOverlines(): ?bool;

    /**
     * Set hasGroups
     *
     * @param boolean $hasGroups
     *
     * @return MenuItemInterface
     */
    public function setHasGroups(?bool $hasGroups) :self;

    /**
     * Get hasGroups
     *
     * @return null|boolean
     */
    public function getHasGroups(): ?bool;

    /**
     * Set icon class
     *
     * @param null|string $iconClass
     *
     * @return MenuItemInterface
     */
    public function setIconClass(?string $iconClass): self;

    /**
     * Get icon class
     *
     * @return null|string
     */
    public function getIconClass(): ?string;

    /**
     * Has icon class
     *
     * @return bool
     */
    public function hasIconClass(): bool;

    /**
     * Set block alias
     *
     * @param null|string $blockAlias
     *
     * @return MenuItemInterface
     */
    public function setBlockAlias(?string $blockAlias): self;

    /**
     * Get block alias
     *
     * @return null|string
     */
    public function getBlockAlias(): ?string;

    /**
     * Has block class
     *
     * @return bool
     */
    public function hasBlockAlias(): bool;

    /**
     * Set isOverviewPage
     *
     * @param null|boolean $isOverviewPage
     *
     * @return MenuItemInterface
     */
    public function setIsOverviewPage(?bool $isOverviewPage): self;

    /**
     * Get isOverviewPage
     *
     * @return boolean
     */
    public function getIsOverviewPage(): ?bool;

    /**
     * Set hideInSlm (hide in second level menu)
     *
     * @param null|boolean $hideInSlm
     *
     * @return MenuItemInterface
     */
    public function setHideInSlm(?bool $hideInSlm): self;

    /**
     * Get hideInSlm (hide in second level menu)
     *
     * @return null|boolean
     */
    public function getHideInSlm(): ?bool;

    public function __toString(): string;
}
