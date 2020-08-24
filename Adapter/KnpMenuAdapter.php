<?php

namespace Prodigious\Sonata\MenuBundle\Adapter;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Manager\MenuManager;
use Prodigious\Sonata\MenuBundle\Manager\MenuItemManager;

use Sonata\PageBundle\Site\SiteSelectorInterface;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\PageInterface;

/**
 * Class KnpMenuAdapter
 *
 * Warning !
 * Using or calling this adapter require to install knplabs/knp-menu-bundle :
 * `composer require knplabs/knp-menu-bundle`
 *
 * @author Joseph LEMOINE <j.lemoine@ludi.cat>
 */
class KnpMenuAdapter
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var MenuManager
     */
    protected $menuManager;

    /**
     * @var MenuItemManager
     */
    protected $menuItemManager;

    /**
     * @var  SiteInterface $site
     */
    protected $site = null;

    /**
     * @var SiteSelectorInterface $siteSelector
     */
    protected $siteSelector;

    /**
     * @var CmsManagerSelectorInterface $cmsManagerSelector
     */
    protected $cmsManagerSelector;

    /**
     * KnpMenuAdapter constructor.
     *
     * @param FactoryInterface $factory
     * @param MenuManager $menuManager
     * @param MenuItemManager $menuItemManager
     * @param SiteSelectorInterface $siteSelector
     * @param CmsManagerSelectorInterface $cmsManagerSelector
     */
    public function __construct(
        FactoryInterface $factory,
        MenuManager $menuManager,
        MenuItemManager $menuItemManager,
        SiteSelectorInterface $siteSelector,
        CmsManagerSelectorInterface $cmsManagerSelector
    ) {
        $this->factory = $factory;
        $this->menuManager = $menuManager;
        $this->menuItemManager = $menuItemManager;
        $this->siteSelector = $siteSelector;
        $this->cmsManagerSelector = $cmsManagerSelector;
    }

    /**
     * Get current site
     *
     * @return Site
     */
    public function getCurrentSite() : ?SiteInterface
    {
        if(is_null($this->site)) {
            $this->site = $this->siteSelector->retrieve();
        }
        return $this->site;
    }

    /**
     * @param string $alias
     * @param int null|$siteId
     *
     * @return ItemInterface
     */
    public function createMenu($alias, array $options = [], $siteId = null)
    {
        $rootOptions = [
            'childrenAttributes' => [],
        ];
        if(!empty($options['menu_class'])) $rootOptions['childrenAttributes'] = ['class' => $options['menu_class']];

        $knp = $this->factory->createItem('root', $rootOptions);

        if(is_null($siteId)) {
            $siteId = $this->getCurrentSite()->getId();
        }

        if($menu = $this->menuManager->loadByAliasAndSiteId($alias, $siteId, MenuManager::STATUS_ENABLED)) {
            $items = $this->menuManager->getRootItems($menu, MenuManager::STATUS_ENABLED);

            foreach ($items as $item) {
                $this->recursiveAddItem($knp, $item, $options);
            }
        }

        return $knp;
    }

    /**
     * @param string $alias
     * @param int null|$siteId
     *
     * @return bool
     */
    public function hasMenu($alias, $siteId = null)
    {
        if(is_null($siteId))
        {
            $siteId = $this->getCurrentSite()->getId();
        }

        if($menu = $this->menuManager->loadByAliasAndSiteId($alias, $siteId, MenuManager::STATUS_ENABLED))
        {
            return true;
        }

        return false;
    }

    /**
     * @param ItemInterface $menu
     * @param MenuItemInterface      $menuItemInterface
     * @return ItemInterface
     */
    protected function recursiveAddItem(ItemInterface $menu, MenuItemInterface $menuItem, array $options = [])
    {
        $pageParameters = [];

        /**
         * @var  PageInterface $page
         */
        if($menuItem->getUrl() == '' && $page = $menuItem->getPage())
        {
            $pageParameters['route'] = $page->getPageAlias() ? $page->getPageAlias() : PageInterface::PAGE_ROUTE_CMS_NAME;
            $pageParameters['routeParameters'] = [];

            if($menuItem->getPageParameter() != '') {
                parse_str($menuItem->getPageParameter() ,  $pageParameters['routeParameters']);
            }

            if(!$this->cmsManagerSelector->isPageViewable($page, $pageParameters['routeParameters'])) return false;

            $pageParameters['routeParameters']['path'] = $page->getUrl();
        }

        $childOptions = array_merge([
            'uri' => $menuItem->getUrl(),
            'label' => $menuItem->getTitle(),
            'childrenAttributes' => [],
            'attributes' => [
                'class'  => $menuItem->getAttributeClass(),
                'style'  => $menuItem->getAttributeStyle(),
                'id'     => $menuItem->getAttributeId(),
            ],
            'linkAttributes' => [
                'target' => $menuItem->getTarget() ? '_blank' : null,
                'class'  => $menuItem->getLinkAttributeClass(),
                'style'  => $menuItem->getLinkAttributeStyle(),
                'id'     => $menuItem->getLinkAttributeId(),
            ],
            'labelAttributes' => [
                'class'  => $menuItem->getLabelAttributeClass(),
                'style'  => $menuItem->getLabelAttributeStyle(),
                'id'     => $menuItem->getLabelAttributeId(),
            ],
        ], $pageParameters);

        if(!empty($options['children_class'])) $childOptions['childrenAttributes'] = ['class' => $options['children_class']];

        $childMenu = $menu->addChild(sprintf('%s.%d', $menu->getName(), $menuItem->getId()), $childOptions);

        $menuItemChilds = $this->menuItemManager->getActiveChildren($menuItem);

        if (count($menuItemChilds)) {
            foreach ($menuItemChilds as $menuItemChild) {
                $this->recursiveAddItem($childMenu, $menuItemChild, $options);
            }
        }

        return $menu;
    }
}