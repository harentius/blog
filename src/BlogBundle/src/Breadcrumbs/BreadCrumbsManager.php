<?php

declare(strict_types=1);

namespace Harentius\BlogBundle\Breadcrumbs;

use Harentius\BlogBundle\Entity\AbstractPost;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Category;
use Harentius\BlogBundle\Entity\Tag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BreadCrumbsManager
{
    /**
     * @var BreadcrumbItem[]
     */
    private $breadcrumbsItems;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->breadcrumbsItems = [];
    }

    /**
     * @param Category $category
     */
    public function buildCategory(Category $category): void
    {
        $this->addHomepage();

        $items = [];
        do {
            $items[] = new BreadcrumbItem(
                $category->getName(),
                $this->urlGenerator->generate('harentius_blog_category', ['slug' => $category->getSlugWithParents()], UrlGeneratorInterface::ABSOLUTE_URL)
            );
        } while ($category = $category->getParent());

        $this->breadcrumbsItems = array_merge($this->breadcrumbsItems, array_reverse($items));
    }

    /**
     * @param Tag $tag
     */
    public function buildTag(Tag $tag): void
    {
        $this->addHomepage();
        $this->breadcrumbsItems[] = new BreadcrumbItem($tag->getName());
    }

    /**
     * @param string $year
     * @param string|null $month
     */
    public function buildArchive(string $year, ?string $month = null): void
    {
        $this->addHomepage();
        $this->breadcrumbsItems[] = new BreadcrumbItem(
            $year,
            $this->urlGenerator->generate('harentius_blog_archive_year', ['year' => $year], UrlGeneratorInterface::ABSOLUTE_URL),
        );

        if ($month) {
            $this->breadcrumbsItems[] = new BreadcrumbItem($month);
        }
    }

    public function buildPost(AbstractPost $post)
    {
        if ($post instanceof Article && $post->getCategory()) {
            $this->buildCategory($post->getCategory());
        } else {
            $this->addHomepage();
        }

        $this->breadcrumbsItems[] = new BreadcrumbItem($post->getTitle());
    }

    private function addHomepage(): void
    {
        $this->breadcrumbsItems[] = new BreadcrumbItem(
            'Homepage',
            $this->urlGenerator->generate('harentius_blog_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL)
        );
    }

    public function getBreadcrumbsItems(): array
    {
        return $this->breadcrumbsItems;
    }
}