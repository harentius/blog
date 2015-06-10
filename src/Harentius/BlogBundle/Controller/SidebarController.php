<?php

namespace Harentius\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SidebarController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Category');

        return $this->render('HarentiusBlogBundle:Sidebar:index.html.twig', [
            'categories' => $categories->childrenHierarchy(null, false, [
                    'decorate' => true,
                    'representationField' => 'slug',
                    'html' => true,
                    'rootOpen' => '<ul>',
                    'rootClose' => '</ul>',
                    'childOpen' => '<li>',
                    'childClose' => '</li>',
                    'nodeDecorator' => function($node) {
                        return sprintf('<a href="%s">%s</a>',
                            $this->generateUrl('blog_category', ['category' => $node['slug']]),
                            $node['name']
                        );
                    }
                ]
            ),
        ]);
    }
}
