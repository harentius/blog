<?php

namespace Harentius\BlogBundle\Sidebar;

use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\ArticleRepository;

class Archive
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return array
     */
    public function getList()
    {
        /** @var Article[] $articles */
        $articles = $this->articleRepository->findAll();
        $list = [];

        foreach ($articles as $article) {
            $publishedAt = $article->getPublishedAt();
            $list[$publishedAt->format('Y')][$publishedAt->format('M')] = $publishedAt->format('F');
        }

        return $list;
    }
}
