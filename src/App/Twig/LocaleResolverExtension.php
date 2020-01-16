<?php

declare(strict_types=1);

namespace App\Twig;

use App\DefaultLocaleResolver;
use Harentius\BlogBundle\Entity\Article;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleResolverExtension extends AbstractExtension
{
    /**
     * @var DefaultLocaleResolver
     */
    private $defaultLocaleResolver;

    /**
     * @param DefaultLocaleResolver $localeResolver
     */
    public function __construct(DefaultLocaleResolver $localeResolver)
    {
        $this->defaultLocaleResolver = $localeResolver;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('resolve_default_locale', [$this, 'resolveDefaultLocale']),
        ];
    }

    /**
     * @param Article $article
     * @return string
     */
    public function resolveDefaultLocale(Article $article): string
    {
        return $this->defaultLocaleResolver->resolveLocale($article);
    }
}
