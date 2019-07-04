<?php

namespace Prodigious\Sonata\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

use Prodigious\Sonata\MenuBundle\Model\MenuItem as BaseMenuItem;
use Prodigious\Sonata\MenuBundle\Entity\MenuItemTranslation;

/**
 * @ORM\Table(name="sonata_menu_item")
 * @ORM\Entity(repositoryClass="Prodigious\Sonata\MenuBundle\Repository\MenuItemRepository")
 * @Gedmo\TranslationEntity(class="Prodigious\Sonata\MenuBundle\Entity\MenuItemTranslation")
 */
class MenuItem extends BaseMenuItem implements TranslatableInterface
{
    use PersonalTranslatableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Prodigious\Sonata\MenuBundle\Entity\MenuItemTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    private  $translations;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();

        parent::__construct();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Remove translation
     *
     * @param MenuItemTranslation $translation
     */
    public function removeTranslation(MenuItemTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * set translation locale
     *
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}