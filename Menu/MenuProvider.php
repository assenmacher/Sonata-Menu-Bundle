<?php
namespace Prodigious\Sonata\MenuBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Prodigious\Sonata\MenuBundle\Adapter\KnpMenuAdapter;

class MenuProvider implements MenuProviderInterface
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var KnpMenuAdapter
     */
    protected $knpMenuAdapter;

    /**
     * MenuProvider constructor.
     *
     * @param FactoryInterface $factory
     * @param KnpMenuAdapter $knpMenuAdapter
     */
    public function __construct(
        FactoryInterface $factory,
        KnpMenuAdapter $knpMenuAdapter
    ) {
        $this->factory = $factory;
        $this->knpMenuAdapter = $knpMenuAdapter;
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array $options
     *
     * @return ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get(string $name, array $options = []): ItemInterface
    {
        return $this->knpMenuAdapter->createMenu($name, $options);
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array $options
     *
     * @return bool
     */
    public function has(string $name, array $options = []): bool
    {
        return $this->knpMenuAdapter->hasMenu($name);
    }
}
