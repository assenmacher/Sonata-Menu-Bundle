<?php
namespace Prodigious\Sonata\MenuBundle\Manager;

use Doctrine\Persistence\ManagerRegistry;
use Sonata\Doctrine\Entity\BaseEntityManager;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Model\MediaManagerInterface;

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
     * @param string $class
     * @param ManagerRegistry $registry
     * @param MediaManagerInterface $mediaManager
     */
    public function __construct($class, ManagerRegistry $registry, MediaManagerInterface $mediaManager)
    {
        parent::__construct($class, $registry);

        $this->mediaManager = $mediaManager;
    }

    public function getActiveChildren(MenuItemInterface $menuItem) :array
    {
        $children = [];

        foreach ($menuItem->getChildren() as $child) {
            if($child->getEnabled() && $child->getLocaleEnabled()) {
                array_push($children, $child);
            }
        }

        return $children;
    }

    /**
     * @param object $entity
     */
    public function loadObjects($entity, $includeChilds = false)
    {
        if(is_null($entity)) return;

        switch (true)
        {
            case $entity instanceof MenuItemInterface:
                if(!$entity->getImage()) $entity->setImage($this->loadMedia($entity->getImageId()));
                break;
        }
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
