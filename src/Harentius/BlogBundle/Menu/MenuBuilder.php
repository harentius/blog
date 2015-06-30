<?php

namespace Harentius\BlogBundle\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Harentius\BlogBundle\Entity\Page;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param FactoryInterface $factory
     * @param EntityManagerInterface $em
     */
    public function __construct(FactoryInterface $factory, EntityManagerInterface $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    /**
     * @return ItemInterface
     */
    public function createMainMenu()
    {
        /** @var Page[] $pages */
        $pages = $this->em->getRepository('HarentiusBlogBundle:Page')->findPublishedOrdered();
        $menu = $this->factory->createItem('root');

        foreach ($pages as $page) {
            $menu->addChild(sprintf('menu.main.%s', $page->getSlug()), [
                'route' => 'blog_show',
                'routeParameters' => ['slug' => $page->getSlug()],
            ]);
        }

        return $menu;
    }
}
