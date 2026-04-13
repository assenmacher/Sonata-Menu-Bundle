<?php
namespace Prodigious\Sonata\MenuBundle\Menu;

use Prodigious\Sonata\MenuBundle\Manager\MenuManager;
use Prodigious\Sonata\MenuBundle\Menu\MenuRegistryInterface;

final class MenuRegistry implements MenuRegistryInterface
{
    /**
     * @var null|string[]
     */
    private $names = null;

    /**
     * @var MenuManager
     */
    private MenuManager $menuManager;

    /**
     * MenuRegistry constructor.
     *
     * @param MenuManager $menuManager
     */
    public function __construct(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
    }

    /**
     * MenuRegistry constructor.
     *
     * @param MenuManager $menuManager
     */
    public function init()
    {
        if(is_null($this->names)) $this->names = $this->menuManager->getSiteGroupedAliases();
    }

    /**
     * {@inheritdoc}
     */
    public function add($menu)
    {
        $this->init();

        $this->names[$menu] = $menu;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasNames()
    {
        $this->init();

        return $this->names;
    }

    /**
     * @param string $name
     */
    public function hasAliasName($name)
    {
        $this->init();

        return array_key_exists($name, $this->names);
    }
}
