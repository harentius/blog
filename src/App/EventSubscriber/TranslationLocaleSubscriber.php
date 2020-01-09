<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\DefaultLocaleResolver;
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
     * @var DefaultLocaleResolver
     */
    private $defaultLocaleResolver;

    /**
     * @param TranslatableListener $translatableListener
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @param DefaultLocaleResolver $localeResolver
     * @throws \Exception
     */
    public function __construct(
        TranslatableListener $translatableListener,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager,
        DefaultLocaleResolver $localeResolver
    ) {
        $this->translatableListener = $translatableListener;
        $this->articleRepository = $articleRepository;
        $this->entityManager = $entityManager;
        $this->defaultLocaleResolver = $localeResolver;
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
                $defaultLocale = $this->defaultLocaleResolver->resolveLocale($article);
                $requestLocale = $request->attributes->get('_locale');

                if ($defaultLocale === DefaultLocaleResolver::EN_LOCALE) {
                    if ($requestLocale) {
                        $this->entityManager->clear();
                    }

                    $oldLocale = $this->translatableListener->getDefaultLocale();
                    $this->translatableListener->setDefaultLocale(DefaultLocaleResolver::EN_LOCALE);
                    $this->articleRepository->findOneBy(['slug' => $request->attributes->get('slug')]);
                    $request->setLocale($defaultLocale);
                    $this->translatableListener->setDefaultLocale($oldLocale);
                } else {
                    $this->entityManager->clear();
                }
            }
        } else {
            $this->translatableListener->setDefaultLocale(DefaultLocaleResolver::EN_LOCALE);
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
}
