<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use App\Sonata\PageBundle\Model\SiteInterface as AppSonataSiteInterface;

interface MenuInterface
{
    /**
     * Set name
     *
     * @param string $name
     *
     * @return MenuInterface
     */
    public function setName(string $name): self;

    /**
     * Get name
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return MenuInterface
     */
    public function setAlias(string $alias): self;

    /**
     * Get alias
     *
     * @return null|string
     */
    public function getAlias(): ?string;

    /**
     * Set site
     *
     * @param null|AppSonataSiteInterface $site
     *
     * @return MenuInterface
     */
    public function setSite(?AppSonataSiteInterface $site): self;

    /**
     * Get site
     *
     * @return null|AppSonataSiteInterface
     */
    public function getSite(): ?AppSonataSiteInterface;

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return MenuInterface
     */
    public function setEnabled(bool $enabled): self;

    /**
     * Get enabled
     *
     * @return null|boolean
     */
    public function getEnabled(): ?bool;

    /**
     * Get locale enabled
     *
     * @return null|boolean
     */
    public function getLocaleEnabled(): ?bool;


    /**
     * Set locale enabled
     *
     * @param boolean $enabled
     *
     * @return MenuInterface
     */
    public function setLocaleEnabled(bool $localeEnabled): self;

    /**
     * Add menuItem
     *
     * @param MenuItemInterface $menuItem
     *
     * @return MenuInterface
     */
    public function addMenuItem(MenuItemInterface $menuItem): self;

    /**
     * Remove menuItem
     *
     * @param MenuItemInterface $menuItem
     *
     * @return MenuInterface
     */
    public function removeMenuItem(MenuItemInterface $menuItem): self;

    /**
     * Set menuItems
     *
     * @param ArrayCollection $menuItems
     *
     * @return MenuInterface
     */
    public function setMenuItems(ArrayCollection $menuItems): self;

    /**
     * Get menuItems
     *
     * @return array
     */
    public function getMenuItems(): array;

    /**
     * Set isMegamenu
     *
     * @param boolean $isMegamenu
     *
     * @return MenuInterface
     */
    public function setIsMegamenu(bool $isMegamenu): self;

    /**
     * Get isMegamenu
     *
     * @return null|boolean
     */
    public function getIsMegamenu(): ?bool;



    public function __toString(): string;
}
