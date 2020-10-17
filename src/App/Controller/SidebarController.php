<?php

declare(strict_types=1);

namespace App\Controller;

use App\LocalizationHelper;
use Gedmo\Translatable\TranslatableListener;
use Harentius\BlogBundle\Entity\Category;
use Harentius\BlogBundle\Entity\CategoryRepository;
use Harentius\BlogBundle\Sidebar\Archive;
use Harentius\BlogBundle\Sidebar\Tags;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SidebarController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var Archive
     */
    private $archive;

    /**
     * @var Tags
     */
    private $tags;

    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @param CategoryRepository $categoryRepository
     * @param Archive $archive
     * @param Tags $tags
     * @param TranslatableListener $translatableListener
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        Archive $archive,
        Tags $tags,
        TranslatableListener $translatableListener
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->archive = $archive;
        $this->tags = $tags;
        $this->translatableListener = $translatableListener;
    }

    /**
     * @param bool $showNumber
     * @return Response
     */
    public function categories($showNumber = true): Response
    {
        $this->translatableListener->setDefaultLocale(LocalizationHelper::DEFAULT_LOCALE);

        return $this->render('@HarentiusBlog/Sidebar/categories.html.twig', [
            'categories' => $this->categoryRepository->notEmptyChildrenHierarchy([
                'decorate' => true,
                'representationField' => 'slug',
                'html' => true,
                'nodeDecorator' => function ($node) use ($showNumber) {
                    /** @var Category $category */
                    $category = $node[0];

                    return sprintf('<a href="%s">%s</a>' . ($showNumber ? ' (%d)' : ''),
                        $this->generateUrl('harentius_blog_category', ['slug' => $category->getSlugWithParents()]),
                        $category->getName(),
                        $node['articles_number']
                    );
                },
            ]),
        ]);
    }

    /**
     * @return Response
     */
    public function archive(): Response
    {
        return $this->render('@HarentiusBlog/Sidebar/archive.html.twig', [
            'archivesList' => $this->archive->getList(),
        ]);
    }

    /**
     * @return Response
     */
    public function tags(): Response
    {
        return $this->render('@HarentiusBlog/Sidebar/tags.html.twig', [
            'tags' => $this->tags->getList(),
        ]);
    }
}
