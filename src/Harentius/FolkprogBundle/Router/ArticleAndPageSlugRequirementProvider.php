<?php

namespace Harentius\FolkprogBundle\Router;

use Doctrine\Common\Cache\CacheProvider;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\ArticleRepository;
use Harentius\BlogBundle\Entity\Page;
use Harentius\BlogBundle\Entity\PageRepository;

class ArticleAndPageSlugRequirementProvider
{
    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @param ArticleRepository $articleRepository
     * @param PageRepository $pageRepository
     * @param CacheProvider $cache
     */
    public function __construct(
        ArticleRepository $articleRepository,
        PageRepository $pageRepository,
        CacheProvider $cache
    ) {
        $this->articleRepository = $articleRepository;
        $this->pageRepository = $pageRepository;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getRequirement()
    {
        $key = 'article_and_page_slug';

        if ($this->cache->contains($key)) {
            return $this->cache->fetch($key);
        }

        /** @var Article[] $articles */
        $articles = $this->articleRepository->findBy(['isPublished' => true]);
        /** @var Page[] $pages */
        $pages = $this->pageRepository->findBy(['isPublished' => true]);
        $slugs = [];

        foreach ($articles as $article) {
            $slugs[] = $article->getSlug();
        }

        foreach ($pages as $page) {
            $slugs[] = $page->getSlug();
        }

        $this->cache->save($key, implode('|', $slugs));

        return $slugs;
    }

    /**
     *
     */
    public function clearAll()
    {
        $this->cache->deleteAll();
    }
}
