<?php

namespace App\Core\Controller\Site;

use App\Core\Repository\Site\NavigationRepositoryQuery;
use App\Core\Sitemap\SitemapBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'sitemap')]
    public function sitemap(Request $request, NavigationRepositoryQuery $query, SitemapBuilder $builder): Response
    {
        $navigations = $query->create()->find();

        $items = [];

        foreach ($navigations as $navigation) {
            if (!$navigation->matchDomain($request->getHost())) {
                continue;
            }

            $items = array_merge(
                $items,
                $builder->build($navigation, $request->getHost())
            );
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');

        return $this->render('@Core/site/sitemap/sitemap.xml.twig', [
            'items' => $items,
        ], $response);
    }
}
