<?php

namespace Prodigious\Sonata\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'sonata/menu';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('config.label_menu', ['translation_domain' => 'ProdigiousSonataMenuBundle'])
                ->add('name', TextType::class,
                    [
                        'label' => 'config.label_name'
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle'
                    ]
                )
                ->add('alias', TextType::class,
                    [
                        'label' => 'config.label_alias'
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle'
                    ]
                )
                ->add('enabled', null,
                    [
                        'label' => 'config.label_enabled',
                        'required' => false,
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
                    ]
                )
                ->add('localeEnabled', null,
                    [
                        'label' => 'config.label_locale_enabled',
                        'required' => false,
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
                    ]
                )
            ->end()
        ->end();

        if($this->getConfigurationPool()->getContainer()->hasParameter('sonata.page.page.class'))
        {
            $formMapper
                ->with('config.label_menu', ['translation_domain' => 'ProdigiousSonataMenuBundle'])
                    ->add('site', ModelType::class,
                        [
                            'label' => 'config.label_site',
                            'required' => true,
                            'btn_add' => false,
                            'help' => 'If you change the site, all existing connections between menuitems and pages will be lost!'
                        ],
                        [
                            'translation_domain' => 'ProdigiousSonataMenuBundle'
                        ]
                    )
                ->end();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, ['label' => 'config.label_id', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
            ->addIdentifier('alias', null, ['label' => 'config.label_alias', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
            ->addIdentifier('name', null, ['label' => 'config.label_name', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
            ->add('site.name', null, ['label' => 'config.label_site', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
            ->add('enabled', null, ['label' => 'config.label_enabled', 'translation_domain' => 'ProdigiousSonataMenuBundle', 'editable' => true])
            ->add('localeEnabled', null, ['label' => 'config.label_locale_enabled', 'translation_domain' => 'ProdigiousSonataMenuBundle', 'editable' => true])
        ;

        $listMapper->add('_action', 'actions', [
            'label' => 'config.label_modify',
            'translation_domain' => 'ProdigiousSonataMenuBundle',
            'actions' => [
                'edit' => [],
                'delete' => [],
                'items' => ['template' => '@ProdigiousSonataMenu/CRUD/list__action_edit_items.html.twig', 'route' => 'items']
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('alias')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('items', $this->getRouterIdParameter().'/items');
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setTemplate('edit', '@ProdigiousSonataMenu/CRUD/edit.html.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function toString($object)
    {
        return $object instanceof MenuInterface ? $object->getName() : $this->getTranslator()->trans("config.label_menu", array(), 'ProdigiousSonataMenuBundle');
    }

    /**
     * @inheritdoc
     */
    public function prePersist($object)
    {
        parent::prePersist($object);
        $this->setMenuItems($object);
    }

    /**
     * @inheritdoc
     */
    public function preUpdate($object)
    {
        parent::prePersist($object);
        $this->setMenuItems($object);
        $this->removePageFromMenuItemsIfNessery($object);
    }

    /**
     * @param MenuInterface $object
     */
    protected function setMenuItems(MenuInterface $object)
    {
        foreach ($object->getMenuItems() as $menuItem) {
            $menuItem->setMenu($object);
        }

    }

    /**
     * @param MenuInterface $object
     */
    protected function removePageFromMenuItemsIfNessery(MenuInterface $object)
    {
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $originalData = $em->getUnitOfWork()->getOriginalEntityData($object);

        if(!is_null($originalData['site']) && $originalData['site']->getId() !== $object->getSite()->getId()) {
            foreach ($object->getMenuItems() as $menuItem) {
                $menuItem->setPage(null);
            }
        }
    }
}
