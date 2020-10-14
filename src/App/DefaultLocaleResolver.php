<?php

declare(strict_types=1);

namespace App;

use Harentius\BlogBundle\Entity\Article;

class DefaultLocaleResolver
{
    /**
     * @var string
     */
    private $enDefaultSince;

    /**
     * @var string
     */
    private $primaryLocale;

    /**
     * @param string $enDefaultSince
     * @param string $primaryLocale
     * @throws \Exception
     */
    public function __construct(string $enDefaultSince, string $primaryLocale)
    {
        $this->enDefaultSince = new \DateTime($enDefaultSince);
        $this->primaryLocale = $primaryLocale;
    }

    /**
     * @param Article $article
     * @return string
     */
    public function resolveLocale(Article $article): string
    {
        if ($article->getCreatedAt() <= $this->enDefaultSince) {
            return LocalizationHelper::FALLBACK_LOCALE;
        }

        return $this->primaryLocale;
    }
}
