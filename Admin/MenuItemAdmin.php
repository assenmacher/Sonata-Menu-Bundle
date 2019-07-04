<?php

namespace Prodigious\Sonata\MenuBundle\Admin;

use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Form\Type\MenuItemSelectorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuItemAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'sonata/menu/menu-item';

    /**
     * @var string
     */
    protected $menuClass;

    public function __construct(string $code, string $class, string $baseControllerName, string $menuClass)
    {
        parent::__construct(
            $code,
            $class,
            $baseControllerName
        );

        $this->menuClass = $menuClass;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('toggle', $this->getRouterIdParameter().'/toggle');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $subject = $this->getSubject();

        if(empty($subject->getMenu()) && $menuId = $this->getRequest()->get('menu', 0))
        {
            $menuManager = $this->getConfigurationPool()->getContainer()->get('prodigious_sonata_menu.manager');

            if($menu = $menuManager->load($menuId))
            {
                $subject->setMenu($menu);
            }
        }

        $formMapper
            ->with('config.label_menu_item',['class' => 'col-md-6', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
                ->add('name', TextType::class,
                    [
                        'label' => 'config.label_name',
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
                    ]
                )
                ->add('title', TextType::class,
                    [
                        'label' => 'config.label_title',
                        'attr' => ['style' => 'border:1px solid #ec6d36;'],
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
                    ]
                )
                ->add('menu', ModelType::class,
                    [
                        'label' => 'config.label_menu',
                        'required' => true,
                        'btn_add' => false,
                        'disabled' => true,
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
                    ]
                )
                ->add('parent', MenuItemSelectorType::class,
                    [
                        'menu' => $subject->getMenu(),
                        'model_manager' => $this->getModelManager(),
                        'class' => $this->getClass(),
                        'label' => 'config.label_parent',
                        'required' => false,
                        'btn_add' => false,
                        'placeholder' => 'config.label_select',
                        'property' => 'levelIndentedName',
                        'filter_choice' => ['id' => $subject->getId()]
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
                    ]
                )
                ->add('classAttribute', TextType::class,
                    [
                        'label' => 'config.label_class_attribute',
                        'required' => false,
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle',
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
        ;

        if($this->getConfigurationPool()->getContainer()->hasParameter('sonata.page.page.class'))
        {
            $pageAdmin = $this->getConfigurationPool()->getContainer()->get('sonata.page.admin.page');

            $formMapper
                ->with('config.label_menu_link', ['class' => 'col-md-6', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
                    ->add('page', \Sonata\PageBundle\Form\Type\PageSelectorType::class,
                        [
                            'label' => 'config.label_page',
                            'site' => $subject->getMenu()->getSite() ?: null,
                            'model_manager' => $pageAdmin->getModelManager(),
                            'class' => $pageAdmin->getClass(),
                            'required' => false,
                            'btn_add' => false,
                            'property' => 'levelIndentedName',
                        ],
                        [
                            'translation_domain' => 'ProdigiousSonataMenuBundle'
                        ]
                    )
                    ->add('pageParameter', TextType::class,
                        [
                            'label' => 'config.label_page_parameter',
                            'required' => false,
                            'attr' => ['style' => 'border:1px solid #ec6d36;'],
                            'help' => 'Only the parameter string, no leading \'?\'.',
                        ],
                        [
                            'translation_domain' => 'ProdigiousSonataMenuBundle'
                        ]
                    )
                ->end();
        }


        $formMapper
            ->with('config.label_menu_link', ['class' => 'col-md-6', 'translation_domain' => 'ProdigiousSonataMenuBundle'])
                ->add('url', TextType::class,
                    [
                        'label' => 'config.label_custom_url',
                        'required' => false,
                        'attr' => ['style' => 'border:1px solid #ec6d36;'],
                        'help' => 'Including protocol like http:// and parameters',
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle'
                    ]
                )
                ->add('target', null,
                    [
                        'label' => 'config.label_target',
                        'required' => false,
                    ],
                    [
                        'translation_domain' => 'ProdigiousSonataMenuBundle'
                    ]
                )
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null,
                [
                    'label' => 'config.label_name',
                    'translation_domain' => 'ProdigiousSonataMenuBundle',
                ]
            )
            ->add('menu', null,
                [],
                EntityType::class,
                [
                    'class'    => $this->menuClass,
                    'choice_label' => 'name',
                ]
            )
            ->add('enabled', null, ['editable' => true])
            ->add('localeEnabled', null, ['editable' => true])
            ->add('_action', 'actions',
                [
                    'label' => 'config.label_modify',
                    'translation_domain' => 'ProdigiousSonataMenuBundle',
                    'actions' => [
                        'edit' => [],
                        'delete' => [],
                    ]
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('menu', null, [], EntityType::class,
                [
                    'class' => $this->menuClass,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function toString($object)
    {
        return $object instanceof MenuItemInterface ? $object->getName() : $this->getTranslator()->trans("config.label_menu_item", array(), 'ProdigiousSonataMenuBundle');
    }

}
