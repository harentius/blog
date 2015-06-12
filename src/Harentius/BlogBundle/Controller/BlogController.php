<?php

namespace Harentius\BlogBundle\Controller;

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
        $articles = [];

        return $this->render('HarentiusBlogBundle:Blog:list.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function showAction()
    {

    }
}
