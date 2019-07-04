<?php

namespace Prodigious\Sonata\MenuBundle\Form\Type;

use Sonata\AdminBundle\Form\Type\ModelType;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Prodigious\Sonata\MenuBundle\Manager\MenuItemManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuItemSelectorType extends AbstractType
{
    /**
     * @var MenuItemManager
     */
    protected $manager;

    protected $foundChoices = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$this->foundChoices)
        {
            $builder->setAttribute('choice_list', new ArrayChoiceList([]));
            $builder->setDisabled(true);
        }
    }
    /**
     * @param MenuItemManager $manager
     */
    public function __construct(MenuItemManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $that = $this;

        $resolver->setDefaults([
            'menu' => null,
            'choices' => static function (Options $opts, $previousValue) use ($that)
            {
                $choices = $that->getChoices($opts);
                if(!empty($choices)) $that->foundChoices = true;
                return $choices;
            },
            'choice_translation_domain' => false,
            'filter_choice' => [
                'id' => null,
            ],
        ]);
    }


    /**
     * @param Options $options
     *
     * @return array
     */
    public function getChoices(Options $options) :array
    {
        if (!$options['menu'] instanceof MenuInterface) {
            return [];
        }

        $choices = [];

        foreach ($options['menu']->getMenuItems() as $menuItem)
        {
            if(!empty($menuItem->getParent())) continue;
            if($options['filter_choice']['id'] && $menuItem->getId() == $options['filter_choice']['id']) continue;

            $choices[$menuItem->getId()] = $menuItem;

            $this->childWalker($menuItem, $options, $choices);
        }

        return $choices;
    }

    /**
     * @param MenuItemInterface $menuItem
     * @param array         $options
     * @param array         $choices
     * @param int           $level
     */
    private function childWalker(MenuItemInterface $menuItem, $options, &$choices, $level = 1)
    {
        foreach ($menuItem->getChildren() as $child) {
            if($options['filter_choice']['id'] && $menuItem->getId() == $options['filter_choice']['id']) continue;

            $choices[$child->getId()] = $child->setLevel($level);

            $this->childWalker($child, $options, $choices, $level + 1);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ModelType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'prodigious_menuitem_selector';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}