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

    public function categoryAction()
    {

    }

    public function tagAction()
    {

    }

    /**
     * @return Response
     */
    public function sidebarAction()
    {
        $categories = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Category');

        return $this->render('HarentiusBlogBundle:Blog:sidebar.html.twig', [
            'categories' => $categories->childrenHierarchy(null, false, [
                    'decorate' => true,
                    'representationField' => 'slug',
                    'html' => true,
                ]
            ),
        ]);
    }
}
