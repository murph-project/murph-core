<?php

namespace App\Core\Controller\Setting;

use App\Core\Controller\Admin\AdminController;
use App\Core\Entity\NavigationSetting as Entity;
use App\Core\Event\Setting\NavigationSettingEvent;
use App\Core\Manager\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/navigation_setting')]
class NavigationSettingAdminController extends AdminController
{
    #[Route(path: '/edit/{entity}', name: 'admin_navigation_setting_edit')]
    public function edit(
        Entity $entity,
        EntityManager $entityManager,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): Response {
        $builder = $this->createFormBuilder($entity);
        $event = new NavigationSettingEvent([
            'builder' => $builder,
            'entity' => $entity,
            'options' => [],
        ]);

        $eventDispatcher->dispatch($event, NavigationSettingEvent::FORM_INIT_EVENT);

        $form = $builder->getForm();
        $redirectTo = $request->query->get('redirectTo');
        $session = $request->getSession();
        $lastRequestId = sprintf('setting_request_%s_%s', get_class($entity), $entity->getId());
        $lastRequest = $session->get($lastRequestId);

        if ($lastRequest !== null && !$request->isMethod('POST')) {
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

                return $this->redirectToRoute('admin_site_navigation_show', [
                    'entity' => $entity->getNavigation()->getId(),
                ]);
            }

            $session->set($lastRequestId, $request->request->get('form'));
            $this->addFlash('warning', 'The form is not valid.');

            return $this->redirect(sprintf(
                '%s?data-modal=%s',
                $redirectTo,
                urlencode($request->getUri())
            ));
        }

        return $this->render('@Core/setting/navigation_setting_admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity,
            'options' => $event->getData()['options'],
            'redirectTo' => $redirectTo,
        ]);
    }

    #[Route(path: '/delete/{entity}', name: 'admin_navigation_setting_delete', methods: ['DELETE', 'POST'])]
    public function delete(Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $entityManager->delete($entity);

            $this->addFlash('success', 'The data has been removed.');
        }

        return $this->redirectToRoute('admin_site_navigation_show', [
            'entity' => $entity->getNavigation()->getId(),
        ]);
    }

    public function getSection(): string
    {
        return '';
    }
}
