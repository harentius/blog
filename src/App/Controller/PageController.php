<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        return $this->render('@HarentiusBlog/Page/about.html.twig');
    }
}
