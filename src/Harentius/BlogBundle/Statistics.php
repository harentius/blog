<?php

namespace Harentius\BlogBundle;

use Doctrine\Common\Cache\CacheProvider;

class Statistics
{
    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * Statistics constructor.
     * @param CacheProvider $cache
     */
    public function __construct(CacheProvider $cache)
    {
        $this->cache = $cache;
    }
}
