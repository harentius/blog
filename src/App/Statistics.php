<?php

namespace App;

use App\Entity\ArticleRepository;
use Doctrine\Common\Cache\CacheProvider;

class Statistics
{
    /**
     * Statistics constructor.
     */
    public function __construct(protected CacheProvider $cache, protected ?int $cacheLifetime, private readonly ArticleRepository $articleRepository)
    {
    }

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
