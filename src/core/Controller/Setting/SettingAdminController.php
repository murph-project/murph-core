<?php

namespace App\Core\Controller\Setting;

use App\Core\Controller\Admin\AdminController;
use App\Core\Entity\Setting as Entity;
use App\Core\Event\Setting\SettingEvent;
use App\Core\Manager\EntityManager;
use App\Core\Repository\SettingRepositoryQuery as RepositoryQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/setting')]
class SettingAdminController extends AdminController
{
    #[Route(path: '/{page}', name: 'admin_setting_index', requirements: ['page' => '\d+'])]
    public function index(
        RepositoryQuery $query,
        EventDispatcherInterface $eventDispatcher,
        Request $request,
        int $page = 1
    ): Response {
        $eventDispatcher->dispatch(new SettingEvent(), SettingEvent::INIT_EVENT);

        $pager = $query
            ->orderBy('.section, .label')
            ->paginate($page)
        ;

        return $this->render('@Core/setting/setting_admin/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    #[Route(path: '/edit/{entity}', name: 'admin_setting_edit')]
    public function edit(
        Entity $entity,
        EntityManager $entityManager,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): Response {
        $builder = $this->createFormBuilder($entity);
        $event = new SettingEvent([
            'builder' => $builder,
            'entity' => $entity,
            'options' => [],
        ]);

        $eventDispatcher->dispatch($event, SettingEvent::FORM_INIT_EVENT);

        $form = $builder->getForm();
        $redirectTo = $request->query->get('redirectTo');
        $session = $request->getSession();
        $lastRequestId = sprintf('setting_request_%s_%s', get_class($entity), $entity->getId());
        $lastRequest = $session->get($lastRequestId);
        $options = $entity->getOptions();
        $optionView = $options['view'] ?? 'modal';

        if (null !== $lastRequest && !$request->isMethod('POST')) {
            $fakeRequest = Request::create(
                uri: $request->getUri(),
                method: 'POST',
                parameters: [$form->getName() => $lastRequest]
            );

            $form->handleRequest($fakeRequest);
            $session->remove($lastRequestId);
        }

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $entityManager->update($entity);
                $session->remove($lastRequestId);
                $entityManager->update($entity);
                $this->addFlash('success', 'The data has been saved.');

                return $this->redirectToRoute('admin_setting_index');
            }

            $session->set($lastRequestId, $request->request->get('form'));
            $this->addFlash('warning', 'The form is not valid.');

            if ($optionView === 'modal') {
                return $this->redirect(sprintf(
                    '%s?data-modal=%s',
                    $redirectTo,
                    urlencode($request->getUri())
                ));
            }
        }

        return $this->render('@Core/setting/setting_admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity,
            'options' => $event->getData()['options'],
            'redirectTo' => $redirectTo,
        ]);
    }

    #[Route(path: '/delete/{entity}', name: 'admin_setting_delete', methods: ['DELETE', 'POST'])]
    public function delete(Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $entityManager->delete($entity);

            $this->addFlash('success', 'The data has been removed.');
        }

        return $this->redirectToRoute('admin_setting_index');
    }

    public function getSection(): string
    {
        return 'setting';
    }
}
