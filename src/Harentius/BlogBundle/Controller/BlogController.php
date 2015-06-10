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

    public function categoryAction($category)
    {

    }

    public function tagAction($category)
    {

    }

    public function showAction()
    {

    }
}
