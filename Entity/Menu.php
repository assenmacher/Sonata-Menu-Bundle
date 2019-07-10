<?php

namespace Prodigious\Sonata\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

use Prodigious\Sonata\MenuBundle\Model\Menu as BaseMenu;
use Prodigious\Sonata\MenuBundle\Entity\MenuTranslation;

/**
 * @ORM\Table(name="sonata_menu")
 * @ORM\Entity(repositoryClass="Prodigious\Sonata\MenuBundle\Repository\MenuRepository")
 * @Gedmo\TranslationEntity(class="Prodigious\Sonata\MenuBundle\Entity\MenuTranslation")
 */
class Menu extends BaseMenu implements TranslatableInterface
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
     *     targetEntity="Prodigious\Sonata\MenuBundle\Entity\MenuTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    private  $translations;

    /**
     * Class constructor
     *
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
     * @param MenuITranslation $translation
     */
    public function removeTranslation(MenuTranslation $translation)
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
