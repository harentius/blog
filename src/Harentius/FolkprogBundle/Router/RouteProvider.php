<?php

namespace Harentius\FolkprogBundle\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Harentius\BlogBundle\Router\RouteProvider as BaseRouteProvider;

class RouteProvider extends BaseRouteProvider
{
    /**
     * @inheritdoc
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        parent::getRouteCollectionForRequest($request);
        $slugProvider = $this->container->get('harentius_folkprog.router.article_and_page_slug_requirement_provider');

        $this->routes->add("harentius_blog_show_en", new Route(
            "{_locale}/{slug}/",
            ['_controller' => 'HarentiusBlogBundle:Blog:show', '_locale' => 'en'],
            ['slug' => $slugProvider->getRequirement()]
        ));

        return $this->routes;
    }
}
