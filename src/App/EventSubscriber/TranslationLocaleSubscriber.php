<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\LocaleResolver;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\TranslatableListener;
use Harentius\BlogBundle\Entity\ArticleRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
     * @var LocaleResolver
     */
    private $localeResolver;

    /**
     * @param TranslatableListener $translatableListener
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @param LocaleResolver $localeResolver
     * @throws \Exception
     */
    public function __construct(
        TranslatableListener $translatableListener,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager,
        LocaleResolver $localeResolver
    ) {
        $this->translatableListener = $translatableListener;
        $this->articleRepository = $articleRepository;
        $this->entityManager = $entityManager;
        $this->localeResolver = $localeResolver;
    }

    /**
     * @param RequestEvent $event
     */
    public function changeTranslationListener(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $route = $request->attributes->get('_route');
        $routes = ['harentius_blog_show', 'harentius_blog_show_default'];
        $isArticleShow = \in_array($route, $routes, true);

        if (!$route || (!$isArticleShow && strpos($route, 'admin_harentius_blog') === false)) {
            return;
        }

        if ($isArticleShow && $request->attributes->get('slug')) {
            $article = $this->articleRepository->findOneBy([
                'slug' => $request->attributes->get('slug'),
            ]);

            if ($article) {
                $locale = $this->localeResolver->resolveLocale($article);

                if ($locale === LocaleResolver::EN_LOCALE) {
                    $this->entityManager->clear();
                    $request->setLocale($locale);
                }
            }
        }

        $this->translatableListener->setDefaultLocale(LocaleResolver::EN_LOCALE);
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
}
