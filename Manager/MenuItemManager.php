<?php
namespace Prodigious\Sonata\MenuBundle\Manager;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Sonata\Doctrine\Entity\BaseEntityManager;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Gedmo\Translatable\TranslatableListener;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;

/**
 * Menuitem manager
 */
class MenuItemManager extends BaseEntityManager
{
    /**
     * @var MediaManagerInterface $mediaManager
     */
    protected MediaManagerInterface $mediaManager;

    /**
     * @var TranslatableListener
     */
    private TranslatableListener $translatableListener;

    /**
     * @param string $class
     * @param ManagerRegistry $registry
     * @param MediaManagerInterface $mediaManager
     * @param TranslatableListener $translatableListener
     */
    public function __construct($class, ManagerRegistry $registry, MediaManagerInterface $mediaManager, TranslatableListener $translatableListener)
    {
        parent::__construct($class, $registry);

        $this->mediaManager = $mediaManager;
        $this->translatableListener = $translatableListener;
    }

    public function getActiveChildren(MenuItemInterface $menuItem) :array
    {
        $children = [];

        foreach ($this->getMenuItemsByParent($menuItem) as $child) {
            if($child->getEnabled() && $child->getLocaleEnabled()) {
                array_push($children, $child);
            }
        }

        return $children;
    }
    public function getMenuItemsByParent(MenuItemInterface $menuItem)
    {
        $query = $this->getRepository()
            ->createQueryBuilder('mi')
            ->select('mi')
            ->where('mi.parent = :parent')
            ->setParameters([
                'parent' => $menuItem,
            ])
            ->orderBy('mi.position')
            ->getQuery();

        //translation query cache issue, only the first call for a transaltion works...
        $query->useQueryCache(false);
        $query->enableResultCache();

        $query->setHint( TranslatableListener::HINT_TRANSLATABLE_LOCALE, $this->translatableListener->getListenerLocale() );
        $query->setHint( Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class );

        return $query->getResult();
    }

    public function getMenuItemsByMenu(MenuInterface $menu)
    {
        $query = $this->getRepository()
            ->createQueryBuilder('mi')
            ->select('mi')
            ->where('mi.menu = :menu')
            ->setParameters([
                'menu' => $menu,
            ])
            ->orderBy('mi.position')
            ->getQuery();

        //translation query cache issue, only the first call for a transaltion works...
        $query->useQueryCache(false);
        $query->enableResultCache();

        $query->setHint( TranslatableListener::HINT_TRANSLATABLE_LOCALE, $this->translatableListener->getListenerLocale() );
        $query->setHint( Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class );

        return $query->getResult();
    }

    /**
     * @param object $entity
     *
     * @return null|object
     */
    public function loadObjects($entity)
    {
        if(is_null($entity)) return $entity;

        switch (true)
        {
            case $entity instanceof MenuItemInterface:
                if(!$entity->hasImage() && $entity->getImageId()) $entity->setImage($this->loadMedia($entity->getImageId()));
                break;
        }

        return $entity;
    }

    /**
     * @param in $id
     *
     * @return null|MediaInterface
     */
    protected function loadMedia(?int $id): ?MediaInterface
    {
        $media = null;

        if (is_int($id) && $id > 0) {
            $media = $this->mediaManager->findOneBy(['id' => $id]);
        }

        return $media;
    }
}
