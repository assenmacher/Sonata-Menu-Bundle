<?php
namespace Prodigious\Sonata\MenuBundle\Manager;

use Sonata\Doctrine\Entity\BaseEntityManager;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;

/**
 * Menuitem manager
 */
class MenuItemManager extends BaseEntityManager
{
    public function getActiveChildren(MenuItemInterface $menuItem) :array
    {
        $children = [];

        foreach ($menuItem->getChildren() as $child) {
            if($child->getEnabled() && $child->getLocaleEnabled()) {
                array_push($children, $child);
            }
        }

        return $children;
    }

}
