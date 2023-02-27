<?php
namespace Prodigious\Sonata\MenuBundle\Block;

use Prodigious\Sonata\MenuBundle\Menu\MenuRegistry;
use Prodigious\Sonata\MenuBundle\Menu\MenuRegistryInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Validator\ErrorElement;
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
class BreadcrumbBlockService extends AbstractAdminBlockService
{
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
     * @param MenuRegistryInterface|null $menuRegistry
     */
    public function __construct($name, EngineInterface $templating, TemplateManagerInterface $templateManager, $menuRegistry = null)
    {
        parent::__construct($name, $templating);

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
            'menu'     => $settings['menu_name'],
            'block'    => $blockContext->getBlock(),
            'context'  => $blockContext,
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

        foreach ($this->getFormSettingsKeys() as $groupName => $group)
        {
            $form->end();
            $form->with($groupName, $group['options']);

            foreach ($group['settings'] as $formSettings)
            {
                list($name, $type, $options) = $formSettings;

                if(!array_key_exists('property_path', $options)) $options['property_path'] = sprintf('settings[%s]', $name);

                $fieldDescriptionOptions = ['translation_domain' => 'ProdigiousSonataMenuBundle'];

                $form->add($name, $type, $options, $fieldDescriptionOptions);

            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        if (($name = $block->getSetting('menu_name')) && '' !== $name && !$this->menuRegistry->hasAliasName($name))
        {
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
            'template' => '@ProdigiousSonataMenu/Block/block_breadcrumb.html.twig',
            'menu_name' => '',
            'safe_labels' => false,
            'current_as_link' => false,
            'include_homepage_link' => false,
            'current_class' => 'active',
            'list_class' => 'breadcrumb_list',
            'list_item_class' => 'breadcrumb_list_item',
            'alias_homepage_link' => '',
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
        if($block->getSetting('include_homepage_link', false))
        {
            if($block->getLocaleSetting('label_homepage_link', '') == '') $block->getLocaleSetting('label_homepage_link', 'Homepage');
            if($block->getSetting('alias_homepage_link', '') == '') $block->setSetting('alias_homepage_link', '/');
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
        if($block->getSetting('include_homepage_link', false))
        {
            if($block->getLocaleSetting('label_homepage_link', '') == '') $block->setLocaleSetting('label_homepage_link', 'Homepage');
            if($block->getSetting('alias_homepage_link', '') == '') $block->setSetting('alias_homepage_link', '/');
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
            'form.field_group_options' => [
                'options' => [
                 ],
                'settings' => [
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
                    ['list_class', TextType::class, [
                        'required' => false,
                        'label' => 'config.label_list_class',
                    ]],
                    ['list_item_class', TextType::class, [
                        'required' => false,
                        'label' => 'config.label_list_item_class',
                    ]],
                    ['template', TextType::class, [
                        'required' => false,
                        'label' => 'config.label_block_template',
                        'sonata_help'  => 'If not a Bundel path like the default "'.$this->getDefault('template').'", use only the template name/path relative to "/templates/'.$this->templatePath.'" like "block_breadcrumb.html.twig". A block template is nessery, if you leave the field blank, it will be set to the default bundel template.',
                    ]],
                ],
            ], 'config.field_group_options' => [
                'options' => [
                    'translation_domain' => 'ProdigiousSonataMenuBundle',
                ],
                'settings' => [
                    ['include_homepage_link', CheckboxType::class, [
                        'required' => false,
                        'label' => 'config.label_include_homepage_link',
                    ]],
                    ['label_homepage_link', TextType::class, [
                        'required' => false,
                        'label' => 'config.label_label_homepage_link',
                        'attr' => ["style" => "border:1px solid #ec6d36;"],
                        'property_path' => 'localeSettings[label_homepage_link]',
                    ]],
                    ['alias_homepage_link', TextType::class, [
                        'required' => false,
                        'label' => 'config.label_alias_homepage_link',
                        'sonata_help'  => 'If not set, "/" will be used.',
                    ]],
                ],
            ],
        ];
    }
}
