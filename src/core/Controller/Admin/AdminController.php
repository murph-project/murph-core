<?php

namespace App\Core\Controller\Admin;

use App\Core\Murph;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

abstract class AdminController extends AbstractController
{
    #[Route(path: '/_ping', name: '_ping')]
    public function ping()
    {
        return $this->json(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $params = $this->getParameter('core')['site'];

        $parameters = array_merge(
            $parameters,
            [
                'section' => $this->getSection(),
                'site_name' => $params['name'],
                'site_logo' => $params['logo'],
                'murph_version' => Murph::version(),
            ]
        );

        return parent::render($view, $parameters, $response);
    }

    abstract protected function getSection(): string;
}
