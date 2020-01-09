<?php

declare(strict_types=1);

namespace App\Twig;

use App\LocaleResolver;
use Harentius\BlogBundle\Entity\Article;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleResolverExtension extends AbstractExtension
{
    /**
     * @var LocaleResolver
     */
    private $localeResolver;

    /**
     * @param LocaleResolver $localeResolver
     */
    public function __construct(LocaleResolver $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('resolve_locale', [$this, 'resolveLocale']),
        ];
    }

    /**
     * @param Article $article
     * @return string
     */
    public function resolveLocale(Article $article): string
    {
        return $this->localeResolver->resolveLocale($article);
    }
}
