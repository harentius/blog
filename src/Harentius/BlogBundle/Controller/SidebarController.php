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
        return $this->render('HarentiusBlogBundle:Sidebar:categories.html.twig', [
            'categories' => $this->get('harentius_blog.sidebar.categories')->getList([
                'decorate' => true,
                'representationField' => 'slug',
                'html' => true,
                'nodeDecorator' => function($node) {
                    // Silent missing IDE warning
                    return sprintf('<a href=' . '"%s">%s</a> (%d)',
                        $this->generateUrl('harentius_blog_list', [
                            'filtrationType' => 'category',
                            'criteria' => $node['slug']
                        ]),
                        $node['name'],
                        $node['articles_number']
                    );
                }
            ]),
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

    /**
     * @return Response
     */
    public function tagsAction()
    {
        return $this->render('HarentiusBlogBundle:Sidebar:tags.html.twig', [
            'tags' => $this->get('harentius_blog.sidebar.tags')->getList(),
        ]);
    }
}
