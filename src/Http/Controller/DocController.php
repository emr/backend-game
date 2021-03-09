<?php

namespace App\Http\Controller;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DocController
{
    #[Route('/', name: 'doc.index')]
    public function index(ParameterBagInterface $parameterBag): Response
    {
        $file = $parameterBag->get('api_doc_path_html');
        $content = file_get_contents($file);

        if (!$content) {
            throw new NotFoundHttpException("The file \"{$file}\" was not found");
        }

        return new Response($content);
    }

    #[Route('doc.yaml', name: 'doc.yaml')]
    public function yaml(ParameterBagInterface $parameterBag): Response
    {
        $file = $parameterBag->get('api_doc_path_yml');
        $content = file_get_contents($file);

        if (!$content) {
            throw new NotFoundHttpException("The file \"{$file}\" was not found");
        }

        return new Response($content, headers: [
            'Content-Type' => 'application/x-yaml',
        ]);
    }
}
