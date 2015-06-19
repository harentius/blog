<?php

namespace Harentius\BlogBundle\Sidebar;

use Harentius\BlogBundle\Entity\Article;

class Archive
{
    use EntityManagerAwareTrait;

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
