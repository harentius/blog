<?php

namespace App\Router;

use App\LocaleResolver;
use Harentius\BlogBundle\Entity\AbstractPost;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Router\PublicationUrlGenerator as BasePublicationUrlGeneratorAlias;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PublicationUrlGenerator extends BasePublicationUrlGeneratorAlias
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $primaryLocale;

    /**
     * @var LocaleResolver
     */
    private $localeResolver;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param LocaleResolver $localeResolver
     * @param string $primaryLocale
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        LocaleResolver $localeResolver,
        string $primaryLocale
    ) {
        parent::__construct($urlGenerator, $primaryLocale);
        $this->urlGenerator = $urlGenerator;
        $this->localeResolver = $localeResolver;
        $this->primaryLocale = $primaryLocale;
    }

    /**
     * @param AbstractPost $post
     * @param string $locale
     * @param int $referenceType
     * @return string
     */
    public function generateUrl(
        AbstractPost $post,
        string $locale,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string {
        $primaryLocale = $this->primaryLocale;

        if ($post instanceof Article) {
            $primaryLocale = $this->localeResolver->resolveLocale($post);
        }

        if ($locale === $primaryLocale) {
            return $this->urlGenerator->generate('harentius_blog_show_default', [
                'slug' => $post->getSlug(),
            ], $referenceType);
        }

        return $this->urlGenerator->generate('harentius_blog_show', [
            'slug' => $post->getSlug(),
            '_locale' => $locale,
        ], $referenceType);
    }
}
