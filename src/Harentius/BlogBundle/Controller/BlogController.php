<?php

namespace Harentius\BlogBundle\Controller;

use Doctrine\ORM\EntityManager;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Page;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $homepage = $this->get('harentius_blog.homepage');

        return $this->render('HarentiusBlogBundle:Blog:index.html.twig', [
            'page' => $homepage->getPage(),
            'articles' => $this->knpPaginateCustomPerPage(
                $request,
                $homepage->getFeed(),
                $this->getParameter('harentius_blog.homepage.feed.number')
            ),
        ]);
    }

    /**
     * @param Request $request
     * @param string $filtrationType
     * @param string $criteria
     * @return Response
     */
    public function listAction(Request $request, $filtrationType, $criteria)
    {
        $articlesRepository = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Article');

        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Homepage', $this->generateUrl('harentius_blog_homepage'));
        $noIndex = false;
        $parent = null;

        switch ($filtrationType) {
            case 'category':
                $category = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Category')
                    ->findOneBy(['slug' => $criteria])
                ;
                $parent = $category;
                $breadcrumbs->addItem($category->getName());
                $articlesQuery = $articlesRepository->findPublishedByCategoryQuery($category);
                break;
            case 'tag':
                $tag = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Tag')
                    ->findOneBy(['slug' => $criteria])
                ;
                $parent = $tag;
                $breadcrumbs->addItem($tag->getName());
                $articlesQuery = $articlesRepository->findByTagQuery($tag);
                $noIndex = true;
                break;
            default:
                throw $this->createNotFoundException('Unknown filtration type');
        }

        return $this->render('HarentiusBlogBundle:Blog:list.html.twig', [
            'articles' => $this->knpPaginate($request, $articlesQuery),
            'parent' => $parent,
            'noIndex' => $noIndex,
        ]);
    }

    /**
     * @param Request $request
     * @param string $year
     * @param string $month
     * @return Response
     */
    public function archiveAction(Request $request, $year, $month = null)
    {
        $articlesRepository = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Article');

        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Homepage', $this->generateUrl('harentius_blog_homepage'));
        $breadcrumbs->addItem($year, $this->generateUrl('harentius_blog_archive_year', ['year' => $year]));

        if ($month) {
            $breadcrumbs->addItem($this->numberToMonth($month, $request->getLocale()));
        }

        $articlesQuery = $articlesRepository->findPublishedByYearMonthQuery($year, $month);

        return $this->render('HarentiusBlogBundle:Blog:list.html.twig', [
            'articles' => $this->knpPaginate($request, $articlesQuery),
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    public function showAction($slug)
    {
        $entity = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Article')
            ->findOneBy(['slug' => $slug])
        ;

        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Homepage', $this->generateUrl('harentius_blog_homepage'));

        if ($entity) {
            $category = $entity->getCategory();

            $breadcrumbs->addItem($category->getName(), $this->generateUrl('harentius_blog_list', [
                'filtrationType' => 'category',
                'criteria' => $category->getSlug(),
            ]));

            $entity->increaseViewsCount();
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->flush($entity);
        } else {
            $entity = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Page')
                ->findOneBy(['slug' => $slug])
            ;

            if (!$entity) {
                throw $this->createNotFoundException(sprintf("Page '%s' not found", $slug));
            }
        }

        $breadcrumbs->addItem($entity->getTitle());
        $class = get_class($entity);
        $type = strtolower(substr($class, strrpos($class, '\\') + 1));

        return $this->render(sprintf('HarentiusBlogBundle:Blog:show_%s.html.twig', $type), [
            'entity' => $entity,
        ]);
    }

    /**
     * @param Article $article
     * @param string $type
     * @return Response
     */
    public function rateAction(Article $article, $type = 'like')
    {
        return $this->get('harentius_blog.rating')->rate(new Response(), $article, $type);
    }

    /**
     * @param Request $request
     * @param mixed $target
     * @param array $options
     * @return PaginationInterface
     */
    private function knpPaginate(Request $request, $target, array $options = [])
    {
        $maxResults = $this->getParameter('harentius_blog.list.posts_per_page');

        return $this->knpPaginateCustomPerPage($request, $target, $maxResults, $options);
    }

    /**
     * @param Request $request
     * @param mixed $target
     * @param $maxResults
     * @param array $options
     * @return PaginationInterface
     */
    private function knpPaginateCustomPerPage(Request $request, $target, $maxResults, array $options = [])
    {
        /** @var Controller $this */

        if (!isset($options['pageParameterName'])) {
            $options['pageParameterName'] = 'page';
        }

        /** @var PaginatorInterface $paginator */
        $paginator = $this->get('knp_paginator');
        $page = max(1, (int) $request->query->get($options['pageParameterName'], 1));

        return $paginator->paginate($target, $page, $maxResults, $options);
    }

    /**
     * @param string $number
     * @param string $locale
     * @return string
     */
    private function numberToMonth($number, $locale)
    {
        $dateTime = \DateTime::createFromFormat('!m', $number);
        $formatter = \IntlDateFormatter::create(
            $locale,
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            $dateTime->getTimezone()->getName(),
            null,
            'LLLL'
        );

        return mb_convert_case($formatter->format($dateTime), MB_CASE_TITLE, "UTF-8");
    }
}
