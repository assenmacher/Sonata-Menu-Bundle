<?php
namespace Prodigious\Sonata\MenuBundle\Menu;

interface MenuRegistryInterface
{
    /**
     * Adds a new menu.
     *
     * @param string $name
     */
    public function add($name);

    /**
     * Returns all alias names.
     *
     * @return string[]
     */
    public function getAliasNames();
}
