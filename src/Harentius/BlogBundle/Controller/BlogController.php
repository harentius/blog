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
     * @param $filtrationType
     * @param $criteria
     * @return Response
     */
    public function listAction($filtrationType, $criteria)
    {
        return $this->render('HarentiusBlogBundle:Blog:list.html.twig');
    }

    public function showAction()
    {

    }
}
