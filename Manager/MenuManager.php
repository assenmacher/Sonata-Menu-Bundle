<?php

namespace Prodigious\Sonata\MenuBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Prodigious\Sonata\MenuBundle\Manager\MenuItemManager;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Sonata\Doctrine\Entity\BaseEntityManager;

/**
 * Menu manager
 */
class MenuManager extends BaseEntityManager
{
    const STATUS_ENABLED = true;
    const STATUS_DISABLED = false;
    const STATUS_ALL = null;

    const ITEM_ROOT = true;
    const ITEM_CHILD = false;
    const ITEM_ALL = null;

    /**
     * @var MenuItemManager
     */
    protected $menuItemManager;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct($class, ManagerRegistry $registry, MenuItemManager $menuItemManager)
    {
        parent::__construct($class, $registry);

        $this->menuItemManager = $menuItemManager;
    }

    /**
     * get menu item manager
     *
     * @return MenuItemManager
     */
    public function getMenuItemManager() :MenuItemManager
    {
        return $this->menuItemManager;
    }

    /**
     * Load menu by id
     *
     * @param int $id
     * @return Menu
     */
    public function load($id)
    {
        return $this->find($id);;
    }

    /**
     * Load menu by alias
     *
     * @param string $alias
     * @return Menu
     */
    public function loadByAlias($alias)
    {
        return $this->findOneByAlias($alias);;
    }

    /**
     * Remove a menu
     *
     * @param mixed $menu
     */
    public function remove($menu)
    {
        $this->delete($menu);
    }

    /**
     * Get first level menu items
     *
     * @param Menu $menu
     * @return MenuItems[]
     */
    public function getRootItems(MenuInterface $menu, $status)
    {
        return $this->getMenuItems($menu, static::ITEM_ROOT, $status);
    }

    /**
     * Get enabled menu items
     *
     * @param Menu $menu
     * @return MenuItems[]
     */
    public function getEnabledItems(MenuInterface $menu)
    {
        return $this->getMenuItems($menu, static::ITEM_ALL, static::STATUS_ENABLED);
    }

    /**
     * Get disabled menu items
     *
     * @param Menu $menu
     * @return MenuItems[]
     */
    public function getDisabledItems(MenuInterface $menu)
    {
        return $this->getMenuItems($menu, static::ITEM_ALL, static::STATUS_DISABLED);
    }

    /**
     * Get menu items
     *
     * @return MenuItem[]
     */
    public function getMenuItems(MenuInterface $menu, $root = self::ALL_ELEMENTS, $status = self::STATUS_ALL)
    {
        $menuItems = $menu->getMenuItems();

        return array_filter($menuItems, function(MenuItemInterface $menuItem) use ($root, $status) {
            // Check root parameter
            if ($root === static::ITEM_ROOT && null !== $menuItem->getParent()
             || $root === static::ITEM_CHILD && null === $menuItem->getParent()
            ) {
                return;
            }

            // Check status parameter
            if ($status === static::STATUS_ENABLED && !$menuItem->getEnabled()
             || $status === static::STATUS_DISABLED && $menuItem->getEnabled()
            ) {
                return;
            }

            return $menuItem;
        });
    }

    /**
     * Update menu tree
     *
     * @param mixed $menu
     * @param array $items
     *
     * @return bool
     */
    public function updateMenuTree($menu, $items, $parent=null)
    {
        $update = false;

        if(!($menu instanceof MenuInterface)) {
            $menu = $this->load($menu);
        }

        if(!empty($items) && $menu) {

            foreach ($items as $pos => $item) {
                /** @var MenuItem $menuItem */
                $menuItem = $this->getMenuItemManager()->findOneBy(array('id' => $item->id, 'menu' => $menu));

                if($menuItem) {
                    $menuItem
                        ->setPosition($pos)
                        ->setParent($parent)
                    ;

                    $this->getMenuItemManager()->save($menuItem, false);
                }

                if(isset($item->children) && !empty($item->children)) {
                    $this->updateMenuTree($menu, $item->children, $menuItem);
                }
            }

            $this->getMenuItemManager()->getObjectManager()->flush();

            $update = true;
        }

        return $update;
    }

}
