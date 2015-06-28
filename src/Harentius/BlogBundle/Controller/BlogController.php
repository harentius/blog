<?php

namespace Harentius\BlogBundle\Controller;

use Harentius\BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('HarentiusBlogBundle:Blog:index.html.twig');
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

    public function archiveAction($year, $month = null)
    {

    }

    /**
     * @param Article $article
     * @return Response
     */
    public function showAction(Article $article)
    {
        $category = $article->getCategory();

        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('Blog', $this->generateUrl('blog_homepage'));
        $breadcrumbs->addItem($category->getName(), $this->generateUrl('blog_list', [
            'filtrationType' => 'category',
            'criteria' => $category->getSlug(),
        ]));
        $breadcrumbs->addItem($article->getTitle());

        return $this->render('HarentiusBlogBundle:Blog:show.html.twig', [
            'article' => $article
        ]);
    }

    public function menuAction()
    {

    }
}
