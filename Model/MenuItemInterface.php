<?php

namespace Prodigious\Sonata\MenuBundle\Model;


interface MenuItemInterface
{

    public function getName();

    public function getTitle();

    public function getInfoText();

    public function getUrl();

    public function getAttributeClass();

    public function getAttributeStyle();

    public function getAttributeId();

    public function getLinkAttributeClass();

    public function getLinkAttributeStyle();

    public function getLinkAttributeId();

    public function getLabelAttributeClass();

    public function getLabelAttributeStyle();

    public function getLabelAttributeId();

    public function getPosition();

    public function getTarget();

    public function getEnabled();

    public function getLocaleEnabled();

    public function getPage();

    public function getPageParameter();

    public function getPageAnchor();

    public function getParent();

    public function getChildren();

	public function hasChild();

	public function hasParent();

    public function getMenu();

    public function getLevel();

    public function getLevelIndentedName(string $indentedWith = '--');

    public function getImageId();

    public function getImage();

    public function hasImage();

    public function getArticleTags();

    public function hasArticleTags();

    public function __toString();
}
