<?php

namespace Prodigious\Sonata\MenuBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\NonUniqueResultException;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
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
     * @param string $class
     * @param ManagerRegistry $registry
     * @param MenuItemManager $menuItemManager
     */
    public function __construct(string $class, ManagerRegistry $registry, MenuItemManager $menuItemManager)
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
    public function load($id, $status = self::STATUS_ALL)
    {
        return $this->find($id);
    }

    /**
     * get all aliases grouped by aliase with used site names
     *
     * @return array
     */
    public function getSiteGroupedAliases(): array
    {
        $aliases = [];

        foreach($this->findAll() as $menus)
        {
            if(!array_key_exists($menus->getAlias(), $aliases)) $aliases[$menus->getAlias()] = [];

            $aliases[$menus->getAlias()][] = $menus->getSite()->getName();

        }

        foreach($aliases as $key=>$aliase)
        {
            $aliases[$key] = $key.' ('.implode(', ', $aliase).')';
        }

        return $aliases;
    }

    /**
     * Load menu by alias
     *
     * @param string $alias
     * @param string $status
     * @return null|Menu
     */
    public function loadByAlias($alias, $status = self::STATUS_ALL)
    {
        $criteria = ['alias' => $alias];
        if(!is_null($status)) {
            $criteria['enabled'] = $status;
            $criteria['localeEnabled'] = $status;
        }

        $query = $this->getSearchOrderTranslationQuery($criteria);

        try {
            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * Load menu by alias
     *
     * @param string $alias
     * @param int $siteId
     * @param string $status
     * @return null|Menu
     */
    public function loadByAliasAndSiteId($alias, $siteId, $status = self::STATUS_ALL)
    {
        $criteria = ['alias' => $alias, 'site_id' => $siteId];
        if(!is_null($status)) {
            $criteria['enabled'] = $status;
            $criteria['localeEnabled'] = $status;
        }

        $query = $this->getSearchOrderTranslationQuery($criteria);

        try {
            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * Load menu by alias
     *
     * @param array $criteria
     * @return Query $query
     */
    public function getSearchOrderTranslationQuery($criteria = [], array $orderBy = null)
    {
        $queryBuilder = $this->getRepository()
            ->createQueryBuilder('m')
            ->select('m');

        foreach ($criteria as $field=>$value) {
            switch ($field) {
                case 'site_id':
                    $queryBuilder->andWhere('IDENTITY(m.site) = :'.$field);
                    break;
                default:
                    $queryBuilder->andWhere('m.'.$field.' = :'.$field);

            }
        }
        $queryBuilder->setParameters($criteria);

        $query = $queryBuilder->getQuery();

        $query->setHint( Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class );

        return $query;
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
     * Get menu items
     *
     * @return MenuItem[]
     */
    public function getMenuItems(MenuInterface $menu, $root = self::ITEM_ALL, $status = self::STATUS_ALL, $frontendCall = false)
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
            if ($status === static::STATUS_ENABLED && (!$menuItem->getEnabled() || !$menuItem->getLocaleEnabled())) {
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
