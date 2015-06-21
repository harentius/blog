<?php

namespace Harentius\BlogBundle\Controller;

use Harentius\BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

        switch ($filtrationType) {
            case 'category':
                $articles = $articlesRepository->findOneByCategorySlug($criteria);
                break;
            case 'tag':
                $articles = $articlesRepository->findByTagSlug($criteria);
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
     * @ParamConverter("article", options={"mapping": {"slug": "slug"}})
     * @return Response
     */
    public function showAction(Article $article)
    {
        return $this->render('HarentiusBlogBundle:Blog:show.html.twig', [
            'article' => $article
        ]);
    }
}
