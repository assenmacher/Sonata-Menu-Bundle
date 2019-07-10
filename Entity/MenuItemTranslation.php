<?php
namespace Prodigious\Sonata\MenuBundle\Entity;

use Prodigious\Sonata\MenuBundle\Entity\MenuItem;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * Translation Entity for the Page
 *
 * @ORM\Table(name="sonata_menu_item_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_menu_item_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 * @ORM\Entity
 */
class MenuItemTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Prodigious\Sonata\MenuBundle\Entity\MenuItem", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}