<?php

namespace Harentius\BlogBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseCRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CRUDController extends BaseCRUDController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $manager = $this->get('harentius_blog.assets.manager');
        $assetFile = $manager->handleUpload($request->files->get('upload'));

        return $this->render('HarentiusBlogBundle:Admin:ck_upload.html.twig', [
            'func_num' => $request->query->get('CKEditorFuncNum'),
            'uri' => $assetFile->getUri(),
        ]);
    }
}
