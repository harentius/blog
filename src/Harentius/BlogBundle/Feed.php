<?php

namespace Harentius\BlogBundle;

use Harentius\BlogBundle\Entity\ArticleRepository;

class Feed
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var string
     */
    private $category;

    /**
     * @param ArticleRepository $articleRepository
     * @param $category
     */
    public function __construct(ArticleRepository $articleRepository, $category)
    {
        $this->articleRepository = $articleRepository;
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function fetch()
    {
        return $this->articleRepository->findByCategorySlugLimitedQuery($this->category);
    }
}
