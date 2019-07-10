<?php
namespace Prodigious\Sonata\MenuBundle\Menu;

use Prodigious\Sonata\MenuBundle\Manager\MenuManager;
use Prodigious\Sonata\MenuBundle\Menu\MenuRegistryInterface;

final class MenuRegistry implements MenuRegistryInterface
{
    /**
     * @var string[]
     */
    private $names = [];

    /**
     * MenuRegistry constructor.
     *
     * @param MenuManager $menuManager
     */
    public function __construct(MenuManager $menuManager)
    {
        $this->names = $menuManager->getSiteGroupedAliases();
    }

    /**
     * {@inheritdoc}
     */
    public function add($menu)
    {
        $this->names[$menu] = $menu;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasNames()
    {
        return $this->names;
    }

    /**
     * @param string $name
     */
    public function hasAliasName($name)
    {
        return array_key_exists($name, $this->names);
    }
}
