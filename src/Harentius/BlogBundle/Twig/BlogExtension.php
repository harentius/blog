<?php

namespace Harentius\BlogBundle\Twig;

use Doctrine\Common\Cache\ApcCache;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

class BlogExtension extends HttpKernelExtension
{
    /**
     * @var ApcCache
     */
    private $apcCache;

    /**
     * @var int
     */
    private $sidebarCacheLifeTime;

    /**
     * @param FragmentHandler $handler A FragmentHandler instance
     * @param ApcCache $apcCache
     * @param int $sidebarCacheLifeTime
     */
    public function __construct(FragmentHandler $handler, ApcCache $apcCache, $sidebarCacheLifeTime)
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
     * @param $uri
     * @param array $options
     * @return string
     */
    public function renderCached($uri, $options = [])
    {
        if (!$this->apcCache->contains($uri)) {
            $this->apcCache->save($uri, $this->renderFragment($uri, $options));
        }

        return $this->apcCache->fetch($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}
