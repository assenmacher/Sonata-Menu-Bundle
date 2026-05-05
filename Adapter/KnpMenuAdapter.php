<?php

namespace Prodigious\Sonata\MenuBundle\Adapter;

use App\Sonata\PageBundle\Route\CmsPageRouteProvider;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Manager\MenuManager;
use Prodigious\Sonata\MenuBundle\Manager\MenuItemManager;

use Sonata\PageBundle\Site\SiteSelectorInterface;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use App\Sonata\PageBundle\Model\SiteInterface as AppSonataSiteInterface;
use App\Sonata\PageBundle\Model\PageInterface as AppSonataPageInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class KnpMenuAdapter
 *
 * Warning !
 * Using or calling this adapter requires to install knplabs/knp-menu-bundle :
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
     * @var  AppSonataSiteInterface $site
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
     * @var CmsPageRouteProvider
     */
    protected $cmsPageRouteProvider;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * KnpMenuAdapter constructor.
     *
     * @param FactoryInterface $factory
     * @param MenuManager $menuManager
     * @param MenuItemManager $menuItemManager
     * @param SiteSelectorInterface $siteSelector
     * @param CmsManagerSelectorInterface $cmsManagerSelector
     * @param CmsPageRouteProvider $cmsPageRouteProvider
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(
        FactoryInterface $factory,
        MenuManager $menuManager,
        MenuItemManager $menuItemManager,
        SiteSelectorInterface $siteSelector,
        CmsManagerSelectorInterface $cmsManagerSelector,
        CmsPageRouteProvider $cmsPageRouteProvider,
        RouterInterface $router,
        RequestStack $requestStack
    ) {
        $this->factory = $factory;
        $this->menuManager = $menuManager;
        $this->menuItemManager = $menuItemManager;
        $this->siteSelector = $siteSelector;
        $this->cmsManagerSelector = $cmsManagerSelector;
        $this->cmsPageRouteProvider = $cmsPageRouteProvider;
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * Get current site
     *
     * @return null|AppSonataSiteInterface
     */
    public function getCurrentSite() : ?AppSonataSiteInterface
    {
        if(is_null($this->site)) {
            $this->site = $this->siteSelector->retrieve();
        }
        return $this->site;
    }

    /**
     * @param string $alias
     * @param array $options
     * @param int null|$siteId
     *
     * @return ItemInterface
     */
    public function createMenu(string $alias, array $options = [], $siteId = null): ItemInterface
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

            $knp->setExtra('menu', $menu);

            foreach ($items as $item)
            {
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
    public function hasMenu(string $alias, $siteId = null): bool
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
     * @param MenuItemInterface $menuItemInterface
     * @param array $options
     * @return null|ItemInterface
     */
    protected function recursiveAddItem(ItemInterface $menu, MenuItemInterface $menuItem, array $options = []): ?ItemInterface
    {
        $routes = [];
        $uri    = '';

        /**
         * @var  AppSonataPageInterface $page
         */
        if($menuItem->getUrl() == '')
        {
            if($page = $menuItem->getPage()) {

                if($page instanceof Proxy && !$page->__isInitialized()) {
                    $page = $this->cmsManagerSelector->retrieve()->getPageById($page->getId(), false);
                }

                $routeParameters = [];

                if ($menuItem->getPageParameter() != '') {
                    $pageParameter = [];
                    parse_str($menuItem->getPageParameter(), $pageParameter);

                    $routeParameters = array_merge($pageParameter, $routeParameters);
                }

                if ($menuItem->getPageAnchor() != '') {
                    $routeParameters['_fragment'] = $menuItem->getPageAnchor();
                }

                try {
                    $route = $this->cmsPageRouteProvider->getRouteByName($page, $routeParameters);

                    $generateParameter = array_merge($routeParameters, [RouteObjectInterface::ROUTE_OBJECT => $route]);

                    $uri = $this->router->generate(RouteObjectInterface::OBJECT_BASED_ROUTE_NAME, $generateParameter);

                    $pathVariables = $route->compile()->getPathVariables();

                    //remove none route specific parameters
                    $routeParameters = array_intersect_key($routeParameters, array_flip($pathVariables));

                    $routeParameters['path'] = $page->getUrl();

                    $routes = [
                        [
                            'route' => AppSonataPageInterface::PAGE_ROUTE_CMS_NAME,
                            'parameters' => $routeParameters,
                        ]
                    ];
                } catch (\Exception $e) {
                    return null;
                }

            }
            else if($menuItem->getPageAnchor())
            {
                $uri = '#'.$menuItem->getPageAnchor();
            }
        }
        else
        {
            $uri = $menuItem->getUrl();
        }

        if(!empty($options['load_objects'])) $this->menuItemManager->loadObjects($menuItem);

        $external = false;

        if($host = parse_url($uri, PHP_URL_HOST))
        {
            if($request = $this->requestStack->getCurrentRequest())
            {
                if(strtolower($host) !== strtolower($request->getHost())) $external = true;
            }
        }

        $childOptions = [
            'uri' => $uri,
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
            'extras' => [
                'menuItem' => $menuItem,
                'routes'   => $routes,
                'external' => $external,
            ],
        ];

        if(!empty($options['children_class'])) $childOptions['childrenAttributes'] = ['class' => $options['children_class']];

        $childMenu = $menu->addChild(sprintf('%s.%d', $menu->getName(), $menuItem->getId()), $childOptions);

        $menuItemChilds = $this->menuItemManager->getActiveChildren($menuItem);

        if (count($menuItemChilds))
        {
            foreach ($menuItemChilds as $menuItemChild)
            {
                $this->recursiveAddItem($childMenu, $menuItemChild, $options);
            }
        }

        if($childMenu->count() === 0)
        {
            if(empty($childMenu->getUri()))
            {
                $menu->removeChild($childMenu);
            }
        }
        else if($childMenu->count() === 1)
        {
            if(!$childMenu->getFirstChild()->count() && $childMenu->getUri() === $childMenu->getFirstChild()->getUri())
            {
                $childMenu->removeChild($childMenu->getFirstChild());
            }
        }

        return $menu;
    }
}
