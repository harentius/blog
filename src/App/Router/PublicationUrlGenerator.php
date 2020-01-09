<?php

namespace App\Router;

use App\DefaultLocaleResolver;
use Harentius\BlogBundle\Entity\AbstractPost;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\TranslationRepository;
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
     * @var DefaultLocaleResolver
     */
    private $defaultLocaleResolver;

    /**
     * @var TranslationRepository
     */
    private $translationRepository;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param DefaultLocaleResolver $localeResolver
     * @param TranslationRepository $translationRepository
     * @param string $primaryLocale
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        DefaultLocaleResolver $localeResolver,
        TranslationRepository $translationRepository,
        string $primaryLocale
    ) {
        parent::__construct($urlGenerator, $primaryLocale);
        $this->urlGenerator = $urlGenerator;
        $this->defaultLocaleResolver = $localeResolver;
        $this->primaryLocale = $primaryLocale;
        $this->translationRepository = $translationRepository;
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
        $availableTranslations = $this->translationRepository->findTranslations($post);

        if ($post instanceof Article) {
            $primaryLocale = $this->defaultLocaleResolver->resolveLocale($post);
        }

        if (!\in_array($locale, $availableTranslations, true)) {
            $locale = $primaryLocale;
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
