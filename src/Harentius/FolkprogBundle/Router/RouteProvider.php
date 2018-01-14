<?php

namespace Harentius\FolkprogBundle\Router;

use Harentius\BlogBundle\Router\CategorySlugProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Harentius\BlogBundle\Router\RouteProvider as BaseRouteProvider;

class RouteProvider extends BaseRouteProvider
{
    /**
     * @var ArticleAndPageSlugRequirementProvider
     */
    private $articleAndPageSlugRequirementProvider;

    /**
     * @param CategorySlugProvider $categorySlugProvider
     * @param ArticleAndPageSlugRequirementProvider $articleAndPageSlugRequirementProvider
     */
    public function __construct(
        CategorySlugProvider $categorySlugProvider,
        ArticleAndPageSlugRequirementProvider $articleAndPageSlugRequirementProvider
    ) {
        parent::__construct($categorySlugProvider);
        $this->articleAndPageSlugRequirementProvider = $articleAndPageSlugRequirementProvider;
    }

    /**
     * @inheritdoc
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        parent::getRouteCollectionForRequest($request);

        $this->routes->add("harentius_blog_show_en", new Route(
            "{_locale}/{slug}/",
            ['_controller' => 'HarentiusBlogBundle:Blog:show', '_locale' => 'en'],
            [
                'slug' => $this->articleAndPageSlugRequirementProvider->getRequirement(),
                '_locale' => 'en',
            ]
        ));

        return $this->routes;
    }
}
