<?php

namespace App\Core\Controller\Editor;

use Fusonic\OpenGraph\Consumer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/admin/editor/editorjs")
 */
class EditorJsController extends AbstractController
{
    /**
     * @Route("/fetch_url", name="admin_editor_editorjs_fetch_url", options={"expose"=true})
     */
    public function fetchUrl(Request $request, HttpClientInterface $client): JsonResponse
    {
        $url = filter_var($request->query->get('url'), FILTER_VALIDATE_URL);
        $datas = [];

        if (!$url) {
            $data['success'] = 0;
        } else {
            try {
                $consumer = new Consumer();
                $response = $client->request('GET', $url);
                $openGraph = $consumer->loadHtml($response->getContent());

                $data = [
                    'success' => 1,
                    'link' => $openGraph->url,
                    'meta' => [
                        'title' => $openGraph->title,
                        'description' => $openGraph->description,
                    ],
                ];

                if (isset($openGraph->images[0])) {
                    $data['meta']['image']['url'] = $openGraph->images[0]->url;
                }
            } catch (\Exception $e) {
                $data['success'] = 0;
            }
        }

        return $this->json($data);
    }
}
