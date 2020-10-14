<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\DefaultLocaleResolver;
use App\LocalizationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\TranslatableListener;
use Harentius\BlogBundle\Entity\ArticleRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TranslationLocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DefaultLocaleResolver
     */
    private $defaultLocaleResolver;

    /**
     * @var LocalizationHelper
     */
    private $localizationHelper;

    /**
     * @param TranslatableListener $translatableListener
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @param DefaultLocaleResolver $localeResolver
     * @param LocalizationHelper $localizationHelper
     */
    public function __construct(
        TranslatableListener $translatableListener,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager,
        DefaultLocaleResolver $localeResolver,
        LocalizationHelper $localizationHelper
    ) {
        $this->translatableListener = $translatableListener;
        $this->articleRepository = $articleRepository;
        $this->entityManager = $entityManager;
        $this->defaultLocaleResolver = $localeResolver;
        $this->localizationHelper = $localizationHelper;
    }

    /**
     * @param RequestEvent $event
     */
    public function changeTranslationListener(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $route = $request->attributes->get('_route');
        $isArticleShow = \in_array($route, ['harentius_blog_show', 'harentius_blog_show_default'], true);
        $isArticleOrPageEdit = \in_array($route, ['admin_harentius_blog_article_edit', 'admin_harentius_blog_page_edit'], true);

        if (!$route || (!$isArticleShow && !$isArticleOrPageEdit)) {
            return;
        }

        if ($isArticleShow) {
            $slug = $request->attributes->get('slug');

            if ($this->localizationHelper->isLegacyArticlesBySlug($slug)) {
                $this->applyFallbackLocale($request);

                return;
            }
        }

        $id = (int) $request->attributes->get('id');

        if ($isArticleOrPageEdit && $this->localizationHelper->isLegacyArticlesById($id)) {
            $this->applyFallbackLocale($request);

            return;
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['changeTranslationListener', 30],
            ],
        ];
    }

    /**
     * @param Request $request
     */
    private function applyFallbackLocale(Request $request): void
    {
        $request->setLocale(LocalizationHelper::FALLBACK_LOCALE);
        $this->translatableListener->setDefaultLocale(LocalizationHelper::FALLBACK_LOCALE);
    }
}
