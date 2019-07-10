<?php

namespace Prodigious\Sonata\MenuBundle\Twig;

use Prodigious\Sonata\MenuBundle\Adapter\KnpMenuAdapter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SonataMenuExtension extends AbstractExtension
{
    /**
     * @var KnpMenuAdapter
     */
    protected $knpMenuAdapter;

    /**
     * SonataMenuExtension constructor.
     *
     * @param KnpMenuAdapter $knpMenuAdapter
     */
    public function __construct(
        KnpMenuAdapter $knpMenuAdapter
    ) {
        $this->knpMenuAdapter = $knpMenuAdapter;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('sonata_menu', [$this->knpMenuAdapter, 'createMenu'])
        ];
    }
}