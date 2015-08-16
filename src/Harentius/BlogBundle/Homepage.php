<?php

namespace Harentius\BlogBundle;

use Harentius\BlogBundle\Entity\ArticleRepository;
use Harentius\BlogBundle\Entity\Page;
use Harentius\BlogBundle\Entity\PageRepository;

class Homepage
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $homepageSlug;

    /**
     * @param ArticleRepository $articleRepository
     * @param PageRepository $pageRepository
     * @param string $category
     * @param string $homepageSlug
     */
    public function __construct(ArticleRepository $articleRepository, PageRepository $pageRepository, $category, $homepageSlug)
    {
        $this->articleRepository = $articleRepository;
        $this->category = $category;
        $this->homepageSlug = $homepageSlug;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return array
     */
    public function getFeed()
    {
        return $this->articleRepository->findByCategorySlugLimitedQuery($this->category);
    }

    /**
     * @return Page|null
     */
    public function getPage()
    {
        return $this->pageRepository->findOneBy([
            'slug' => $this->homepageSlug,
            'isPublished' => true
        ]);
    }
}
