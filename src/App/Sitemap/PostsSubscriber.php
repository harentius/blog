<?php

declare(strict_types=1);

namespace App\Sitemap;

use App\DefaultLocaleResolver;
use Harentius\BlogBundle\Entity\AbstractPost;
use Harentius\BlogBundle\Entity\AbstractPostRepository;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\TranslationRepository;
use Harentius\BlogBundle\Router\PublicationUrlGenerator;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostsSubscriber implements EventSubscriberInterface
{
    /**
     * @var AbstractPostRepository
     */
    private $abstractPostRepository;

    /**
     * @var PublicationUrlGenerator
     */
    private $publicationUrlGenerator;

    /**
     * @var TranslationRepository
     */
    private $translationRepository;

    /**
     * @var DefaultLocaleResolver
     */
    private $defaultLocaleResolver;

    /**
     * @var string
     */
    private $primaryLocale;

    /**
     * @param AbstractPostRepository $abstractPostRepository
     * @param PublicationUrlGenerator $publicationUrlGenerator
     * @param TranslationRepository $translationRepository
     * @param DefaultLocaleResolver $defaultLocaleResolver
     * @param string $primaryLocale
     */
    public function __construct(
        AbstractPostRepository $abstractPostRepository,
        PublicationUrlGenerator $publicationUrlGenerator,
        TranslationRepository $translationRepository,
        DefaultLocaleResolver $defaultLocaleResolver,
        string $primaryLocale
    ) {
        $this->abstractPostRepository = $abstractPostRepository;
        $this->publicationUrlGenerator = $publicationUrlGenerator;
        $this->translationRepository = $translationRepository;
        $this->defaultLocaleResolver = $defaultLocaleResolver;
        $this->primaryLocale = $primaryLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function populate(SitemapPopulateEvent $event)
    {
        $posts = $this->abstractPostRepository->findPublished();

        foreach ($posts as $post) {
            $primaryLocale = $post instanceof Article
                ? $this->defaultLocaleResolver->resolveLocale($post)
                : $this->primaryLocale
            ;
            $this->addUrl($event, $post, $primaryLocale);
            $locales = $this->translationRepository->findTranslations($post);

            foreach ($locales as $locale) {
                $this->addUrl($event, $post, $locale);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     * @param AbstractPost $post
     * @param string $locale
     */
    private function addUrl(SitemapPopulateEvent $event, AbstractPost $post, string $locale): void
    {
        $event->getGenerator()->addUrl(
            new UrlConcrete(
                $this->publicationUrlGenerator->generateUrl($post, $locale, UrlGeneratorInterface::ABSOLUTE_URL),
                $post->getUpdatedAt(),
                UrlConcrete::CHANGEFREQ_MONTHLY,
                0.5
            ),
            'pages'
        );
    }
}
