<?php
namespace Prodigious\Sonata\MenuBundle\Block;

use Prodigious\Sonata\MenuBundle\Menu\MenuRegistry;
use Prodigious\Sonata\MenuBundle\Menu\MenuRegistryInterface;

use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\Form\Type\ImmutableArrayType;
use Sonata\PageBundle\Page\TemplateManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
class MenuBlockService extends AbstractAdminBlockService
{
    /**
     * @var MenuProviderInterface
     */
    protected $menuProvider;

    /**
     * @var MenuRegistryInterface
     */
    protected $menuRegistry;

    /**
     * @var string
     */
    private $templatePath = 'block/menu/';

    /**
     * @var TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @param string                     $name
     * @param EngineInterface            $templating
     * @param TemplateManagerInterface   $templateManager
     * @param MenuProviderInterface      $menuProvider
     * @param MenuRegistryInterface|null $menuRegistry
     */
    public function __construct($name, EngineInterface $templating, TemplateManagerInterface $templateManager, MenuProviderInterface $menuProvider, $menuRegistry = null)
    {
        parent::__construct($name, $templating);

        $this->menuProvider = $menuProvider;
        $this->templateManager = $templateManager;

        if ($menuRegistry instanceof MenuRegistryInterface) {
            $this->menuRegistry = $menuRegistry;
        } elseif (null === $menuRegistry) {
            $this->menuRegistry = new MenuRegistry();
        } else {
            throw new \InvalidArgumentException(sprintf(
                'MenuRegistry must be either null or instance of %s',
                MenuRegistryInterface::class
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $blockContext->getBlock();

        if (!$block->getEnabled() || !$block->getLocaleEnabled()) {
            return new Response();
        }

        $settings = $blockContext->getSettings();

        if(method_exists($block, 'getLocaleSettings'))
        {
            $localeSettings = $block->getLocaleSettings();

            if(is_array($localeSettings)) $settings = array_merge($settings, $localeSettings);
        }

        $responseSettings = [
            'menu' => $settings['menu_name'],
            'menu_path' => [],
            'menu_options' => $this->getMenuOptions($blockContext->getSettings()),
            'block' => $blockContext->getBlock(),
            'context' => $blockContext,
            'settings' => $settings,
        ];

        if ('private' === $blockContext->getSetting('cache_policy')) {
            return $this->renderPrivateResponse($this->getTemplate($blockContext->getTemplate()), $responseSettings, $response);
        }

        return $this->renderResponse($this->getTemplate($blockContext->getTemplate()), $responseSettings, $response);
    }

    /**
     * @param string $template
     *
     * @return string
     */
    protected function getTemplate($template)
    {
        //if bundel template, don't change path
        if(empty($template) || strpos($template, '@') !== false) return $template;

        return $this->templateManager->getSiteTemplatePath($this->templatePath.$template);
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        $form->add('localeSettings', ImmutableArrayType::class, [
            'keys' => [
                ['title', TextType::class, [
                    'label' => 'config.label_title',
                    'attr' => ["style" => "border:1px solid #ec6d36;"]
                ]],
            ],
            'translation_domain' => 'ProdigiousSonataMenuBundle',
            'label' => false,
            'required' => false,
        ]);
        $form->add('settings', ImmutableArrayType::class, [
            'keys' => $this->getFormSettingsKeys(),
            'translation_domain' => 'ProdigiousSonataMenuBundle',
            'label' => false,
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        if (($name = $block->getSetting('menu_name')) && '' !== $name && !$this->menuRegistry->hasAliasName($name)) {
            // If we specified a menu_name, check that it exists
            $errorElement->with('menu_name')
                ->addViolation('prodigious.menu.block.menu.not_existing', ['%name%' => $name])
            ->end();
        }
    }

    /**
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'title_class' => '',
            'cache_policy' => 'public',
            'template' => '@ProdigiousSonataMenu/Block/block_menu.html.twig',
            'menu_name' => '',
            'safe_labels' => false,
            'current_as_link' => false,
            'current_class' => 'active',
            'ancestor_class' => '',
            'first_class' => '',
            'last_class' => '',
            'branch_class' => '',
            'leaf_class' => '',
            'depth' => null,
            'matching_depth' => null,
            'menu_class' => 'menu_list',
            'children_class' => 'menu_list_item',
            'menu_template' => null,
        ];
    }

    /**
     * @param string $field
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getDefault(string $field, $default = '')
    {
        $defaults = $this->getDefaults();

        return array_key_exists($field, $defaults) ? $defaults[$field] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block) {
        if($block->getSetting('template', '') == '')
        {
            $block->setSetting('template', $this->getDefault('template'));
        }
        parent::prePersist($block);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block) {
        if($block->getSetting('template', '') == '')
        {
            $block->setSetting('template', $this->getDefault('template'));
        }
        parent::preUpdate($block);
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->getDefaults());
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (null !== $code ? $code : $this->getName()), false, 'ProdigiousSonataMenuBundle', [
            'class' => 'fa fa-bars',
        ]);
    }

    /**
     * @return array
     */
    protected function getFormSettingsKeys()
    {
        $menuNameOptions = [
            'required'                  => false,
            'label'                     => 'config.label_url',
            'choice_translation_domain' => 'ProdigiousSonataMenuBundle',
            'choices'                   => array_flip($this->menuRegistry->getAliasNames()),
        ];

        return [
            ['title_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_title_class',
            ]],
            ['cache_policy', ChoiceType::class, [
                'label' => 'config.label_cache_policy',
                'choices' => ['Public' => 'public', 'Private' => 'private'],
            ]],
            ['menu_name', ChoiceType::class, $menuNameOptions],
            ['safe_labels', CheckboxType::class, [
                'required' => false,
                'label' => 'config.label_safe_labels',
            ]],
            ['current_as_link', CheckboxType::class, [
                'required' => false,
                'label' => 'config.label_current_as_link',
            ]],
            ['current_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_current_class',
            ]],
            ['ancestor_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_ancestor_class',
            ]],
            ['first_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_first_class',
            ]],
            ['last_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_last_class',
            ]],
            ['branch_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_branch_class',
            ]],
            ['leaf_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_leaf_class',
            ]],
            ['depth', TextType::class, [
                'required' => false,
                'label' => 'config.label_depth',
                'sonata_help'  => 'Leave empty to get all.',
            ]],
            ['matching_depth', TextType::class, [
                'required' => false,
                'label' => 'config.label_matching_depth',
                'sonata_help'  => 'Max depth to show active nodes, leave empty to get all.',
            ]],
            ['menu_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_menu_class',
            ]],
            ['children_class', TextType::class, [
                'required' => false,
                'label' => 'config.label_children_class',
            ]],
            ['template', TextType::class, [
                'required' => false,
                'label' => 'config.label_block_template',
                'sonata_help'  => 'If not a Bundel path like the default "'.$this->getDefault('template').'", use only the template name/path relative to "/templates/'.$this->templatePath.'" like "block_menu.html.twig". A block template is nessery, if you leave the field blank, it will be set to the default bundel template.',
            ]],
            ['menu_template', TextType::class, [
                'required' => false,
                'label' => 'config.label_menu_template',
                'sonata_help'  => 'If not a Bundel path (starting with a "@"), use only the template name/path relative to "/templates/'.$this->templatePath.'" like "menu.html.twig". If you leave the field blank, the default KNP-Menu Bundel template will be used.',
            ]],
        ];
    }

    /**
     * Replaces setting keys with knp menu item options keys.
     *
     * @param array $settings
     *
     * @return array
     */
    protected function getMenuOptions(array $settings)
    {
        $mapping = [
            'current_class' => 'currentClass',
            'ancestor_class' => 'ancestorClass',
            'first_class' => 'firstClass',
            'last_class' => 'lastClass',
            'safe_labels' => 'allow_safe_labels',
            'current_as_link' => 'currentAsLink',
            'branch_class' => 'branch_class',
            'leaf_class' => 'leaf_class',
            'menu_template' => 'template',
            'depth' => 'depth',
            'matching_depth' => 'matchingDepth',
            'menu_class' => 'menu_class',
            'children_class' => 'children_class',
        ];

        $options = [];

        foreach ($settings as $key => $value) {
            if (array_key_exists($key, $mapping) && null !== $value) {
                $options[$mapping[$key]] = $value;
            }
        }

        if(array_key_exists('template', $options)) $options['template'] = $this->getTemplate($options['template']);
        if(array_key_exists('depth', $options)) $options['depth'] = intval($options['depth']);
        if(array_key_exists('matching_depth', $options)) $options['matching_depth'] = intval($options['matching_depth']);

        return $options;
    }
}
