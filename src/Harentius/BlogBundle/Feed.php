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
     * @var int
     */
    private $number;

    /**
     * @param ArticleRepository $articleRepository
     * @param $category
     * @param $number
     */
    public function __construct(ArticleRepository $articleRepository, $category, $number)
    {
        $this->articleRepository = $articleRepository;
        $this->category = $category;
        $this->number = $number;
    }

    /**
     * @return array
     */
    public function fetch()
    {
        if (!$this->category && !$this->number) {
            return [];
        }

        return $this->articleRepository->findByCategorySlugLimited($this->number, $this->category);
    }
}
