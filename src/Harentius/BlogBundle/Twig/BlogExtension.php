<?php

namespace Harentius\BlogBundle\Twig;

use Doctrine\Common\Cache\Cache;
use Harentius\BlogBundle\Rating;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Harentius\BlogBundle\Entity\Article;

class BlogExtension extends HttpKernelExtension
{
    /**
     * @var Cache
     */
    private $apcCache;

    /**
     * @var int
     */
    private $sidebarCacheLifeTime;

    /**
     * @var Rating
     */
    private $rating;

    /**
     * @param FragmentHandler $handler A FragmentHandler instance
     * @param Cache $apcCache
     * @param Rating $rating
     * @param int $sidebarCacheLifeTime
     */
    public function __construct(
        FragmentHandler $handler,
        Cache $apcCache,
        Rating $rating,
        $sidebarCacheLifeTime
    ) {
        parent::__construct($handler);
        $this->apcCache = $apcCache;
        $this->sidebarCacheLifeTime = $sidebarCacheLifeTime;
        $this->rating = $rating;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_cached', [$this, 'renderCached'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('is_article_liked', [$this, 'isArticleLiked']),
            new \Twig_SimpleFunction('is_article_disliked', [$this, 'isArticleDisLiked']),
            new \Twig_SimpleFunction('is_article_rated', [$this, 'isArticleRated']),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('read_more', [$this, 'readMore'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $controllerReference
     * @param array $options
     * @return string
     */
    public function renderCached(ControllerReference $controllerReference, $options = [])
    {
        $key = $controllerReference->controller;

        if (!$this->apcCache->contains($key)) {
            $this->apcCache->save($key, $this->renderFragment($controllerReference, $options));
        }

        return $this->apcCache->fetch($key);
    }

    /**
     * @param $content
     * @param null $url
     * @return string
     */
    public function readMore($content, $url = null)
    {
        if (($length = strpos($content, '<!--more-->')) !== false) {
            $content = substr($content, 0, $length);
        }

        if ($url !== null) {
            $content .= '<a href="' . $url . '">[..]</a>';
        }

        return $content;
    }

    /**
     * @param Article $article
     * @return bool
     */
    public function isArticleLiked(Article $article)
    {
        return $this->rating->isLiked($article);
    }

    /**
     * @param Article $article
     * @return bool
     */
    public function isArticleDisLiked(Article $article)
    {
        return $this->rating->isDisLiked($article);
    }

    /**
     * @param Article $article
     * @return bool
     */
    public function isArticleRated(Article $article)
    {
        return $this->rating->isRated($article);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}
