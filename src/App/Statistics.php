<?php

namespace App;

use App\Entity\ArticleRepository;
use Doctrine\Common\Cache\CacheProvider;

class Statistics
{
    /**
     * @var CacheProvider
     */
    protected $cache;

    /**
     * @var int
     */
    protected $cacheLifetime;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * Statistics constructor.
     * @param CacheProvider $cache
     * @param int|null $cacheLifetime
     * @param ArticleRepository $articleRepository
     */
    public function __construct(CacheProvider $cache, ?int $cacheLifetime, ArticleRepository $articleRepository)
    {
        $this->cache = $cache;
        $this->cacheLifetime = $cacheLifetime;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $key = 'statistics';

        if ($this->cache->contains($key)) {
            return $this->cache->fetch($key);
        }

        $statistics = $this->articleRepository->findStatistics();
        $mostPopularArticle = $this->articleRepository->findMostPopular();

        if ($mostPopularArticle) {
            $statistics['mostPopularArticleData'] = [
                'slug' => $mostPopularArticle->getSlug(),
                'title' => $mostPopularArticle->getTitle(),
                'viewsCount' => $mostPopularArticle->getViewsCount(),
            ];
        }

        $this->cache->save($key, $statistics, $this->cacheLifetime);

        return $statistics;
    }
}
