<?php

namespace Harentius\BlogBundle\Twig;

use Doctrine\Common\Cache\Cache;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

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
     * @param FragmentHandler $handler A FragmentHandler instance
     * @param Cache $apcCache
     * @param int $sidebarCacheLifeTime
     */
    public function __construct(FragmentHandler $handler, Cache $apcCache, $sidebarCacheLifeTime)
    {
        parent::__construct($handler);
        $this->apcCache = $apcCache;
        $this->sidebarCacheLifeTime = $sidebarCacheLifeTime;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_cached', [$this, 'renderCached'], ['is_safe' => ['html']]),
        );
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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}
