<?php

namespace Harentius\BlogBundle\Sidebar;

use Doctrine\ORM\EntityManagerInterface;
use Harentius\BlogBundle\Entity\Article;

class Archive
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getList()
    {
        /** @var Article[] $articles */
        $articles = $this->em->getRepository('HarentiusBlogBundle:Article')->findAll();
        $list = [];

        foreach ($articles as $article) {
            $publishedAt = $article->getPublishedAt();
            $list[$publishedAt->format('Y')][$publishedAt->format('M')] = $publishedAt->format('F');
        }

        return $list;
    }
}
