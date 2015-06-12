<?php

namespace Harentius\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SidebarController extends Controller
{
    /**
     * @return Response
     */
    public function categoriesAction()
    {
        $categories = $this->getDoctrine()->getRepository('HarentiusBlogBundle:Category');

        return $this->render('HarentiusBlogBundle:Sidebar:categories.html.twig', [
            'categories' => $categories->childrenHierarchy(null, false, [
                    'decorate' => true,
                    'representationField' => 'slug',
                    'html' => true,
                    'nodeDecorator' => function($node) {
                        // Silent missing IDE warning
                        return sprintf('<a href=' . '"%s">%s</a>',
                            $this->generateUrl('blog_category', ['category' => $node['slug']]),
                            $node['name']
                        );
                    }
                ]
            ),
        ]);
    }

    /**
     * @return Response
     */
    public function archiveAction()
    {
        return $this->render('HarentiusBlogBundle:Sidebar:archive.html.twig', [
            'archivesList' => $this->get('harentius_blog.sidebar.archive')->getList(),
        ]);
    }
}
