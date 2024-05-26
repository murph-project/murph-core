<?php

namespace App\Core\Controller\Editor;

use App\Core\BuilderBlock\BuilderBlockContainer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/admin/editor/builder_block')]
class BuilderBlockController extends AbstractController
{
    public function __construct(protected TranslatorInterface $translator)
    {
    }

    #[Route(path: '/widgets', name: 'admin_editor_builder_block_widgets', options: ['expose' => true])]
    public function widgets(BuilderBlockContainer $container): JsonResponse
    {
        $data = [];

        foreach ($container->getWidgets() as $widget) {
            $data[$widget->getName()] = $this->translate($widget->toArray());
        }

        return $this->json($data);
    }

    protected function translate(array $data)
    {
        $data['label'] = $this->translator->trans($data['label']);
        $data['category'] = $this->translator->trans($data['category']);

        foreach ($data['settings'] as $key => $value) {
            $data['settings'][$key]['label'] = $this->translator->trans($data['settings'][$key]['label']);
        }

        return $data;
    }
}
