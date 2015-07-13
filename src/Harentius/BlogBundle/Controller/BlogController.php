<?php

namespace Harentius\BlogBundle\Controller;

use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $indexPageContent = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Page')
            ->findOneBy([
                'slug' => $this->getParameter('harentius_blog.homepage.page_slug'),
                'isPublished' => true
            ])
        ;

        return $this->render('HarentiusBlogBundle:Blog:index.html.twig', [
            'page' => $indexPageContent,
        ]);
    }

    /**
     * @param string $filtrationType
     * @param string $criteria
     * @return Response
     */
    public function listAction($filtrationType, $criteria)
    {
        $articlesRepository = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Article');

        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Blog', $this->generateUrl('blog_homepage'));

        switch ($filtrationType) {
            case 'category':
                $category = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Category')
                    ->findOneBy(['slug' => $criteria])
                ;
                $breadcrumbs->addItem($category->getName());
                $articles = $articlesRepository->findPublishedByCategory($category);
                break;
            case 'tag':
                $tag = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Tag')
                    ->findOneBy(['slug' => $criteria])
                ;
                $breadcrumbs->addItem($tag->getName());
                $articles = $articlesRepository->findByTag($tag);
                break;
            default:
                $articles = [];
                break;
        }

        return $this->render('HarentiusBlogBundle:Blog:list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @param string $year
     * @param string $month
     * @return Response
     */
    public function archiveAction($year, $month = null)
    {
        $articlesRepository = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Article');

        return $this->render('HarentiusBlogBundle:Blog:list.html.twig', [
            'articles' => $articlesRepository->findPublishedByYearMonth($year, $month),
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
        $breadcrumbs->addItem('Blog', $this->generateUrl('blog_homepage'));

        if ($entity) {
            $category = $entity->getCategory();

            $breadcrumbs->addItem($category->getName(), $this->generateUrl('blog_list', [
                'filtrationType' => 'category',
                'criteria' => $category->getSlug(),
            ]));
        } else {
            $entity = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Page')
                ->findOneBy(['slug' => $slug])
            ;

            if (!$entity) {
                throw $this->createNotFoundException(sprintf("Page '%s' not found", $slug));
            }
        }

        $breadcrumbs->addItem($entity->getTitle());

        return $this->render('HarentiusBlogBundle:Blog:show.html.twig', [
            'entity' => $entity
        ]);
    }

    public function menuAction()
    {

    }
}
