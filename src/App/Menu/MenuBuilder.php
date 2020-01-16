<?php

namespace App\Menu;

use Harentius\BlogBundle\Menu\MenuBuilder as BaseMenuBuilderAlias;
use Knp\Menu\ItemInterface;

class MenuBuilder extends BaseMenuBuilderAlias
{
    /**
     * @return ItemInterface
     */
    public function createMainMenu(): ItemInterface
    {
        $menu = parent::createMainMenu();
        $children = $menu->getChildren();
        $childrenKeys = array_keys($children);
        array_unshift($childrenKeys, 'About');
        $menu
            ->addChild('About', ['route' => 'app_page_about'])
            ->setLinkAttribute('class', 'nav-link')
        ;

        $menu->reorderChildren($childrenKeys);

        return $menu;
    }
}
