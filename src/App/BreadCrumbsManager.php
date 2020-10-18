<?php

declare(strict_types=1);

namespace App;

use Gedmo\Translatable\TranslatableListener;
use Harentius\BlogBundle\BreadCrumbsManager as BaseBreadCrumbsManager;
use Harentius\BlogBundle\Entity\Category;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BreadCrumbsManager extends BaseBreadCrumbsManager
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    public function __construct(
        Breadcrumbs $breadcrumbs,
        UrlGeneratorInterface $urlGenerator,
        TranslatableListener $translatableListener
    ) {
        parent::__construct($breadcrumbs, $urlGenerator);
        $this->urlGenerator = $urlGenerator;
        $this->breadcrumbs = $breadcrumbs;
        $this->translatableListener = $translatableListener;
    }

    /**
     * @param Category $category
     */
    public function buildCategory(Category $category): void
    {
        $this->translatableListener->setDefaultLocale(LocalizationHelper::DEFAULT_LOCALE);
        parent::buildCategory($category);
    }
}
