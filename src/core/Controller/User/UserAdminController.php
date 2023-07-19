<?php

namespace App\Core\Controller\User;

use App\Core\Controller\Admin\Crud\CrudController;
use App\Core\Crud\CrudConfiguration;
use App\Core\Crud\Field;
use App\Core\Event\Account\PasswordRequestEvent;
use App\Core\Factory\UserFactory as Factory;
use App\Core\Manager\EntityManager;
use App\Core\Security\TokenGenerator;
use App\Entity\User as Entity;
use App\Form\UserType as Type;
use App\Repository\UserRepositoryQuery as RepositoryQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends CrudController
{
    protected ?CrudConfiguration $configuration = null;

    public function index(RepositoryQuery $query, Request $request, Session $session, int $page = 1): Response
    {
        return $this->doIndex($page, $query, $request, $session);
    }

    public function new(Factory $factory, EntityManager $entityManager, Request $request, TokenGenerator $tokenGenerator): Response
    {
        return $this->doNew($factory->create(null, $tokenGenerator->generateToken()), $entityManager, $request);
    }

    public function show(Entity $entity): Response
    {
        return $this->doShow($entity);
    }

    public function filter(Session $session): Response
    {
        return $this->doFilter($session);
    }

    public function edit(Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        return $this->doEdit($entity, $entityManager, $request);
    }

    public function inlineEdit(string $context, string $label, Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        return $this->doInlineEdit($context, $label, $entity, $entityManager, $request);
    }

    public function delete(Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        return $this->doDelete($entity, $entityManager, $request);
    }

    public function requestResetting(Entity $entity, EventDispatcherInterface $eventDispatcher, Request $request): Response
    {
        if ($this->isCsrfTokenValid('resetting_request'.$entity->getId(), $request->request->get('_token'))) {
            $eventDispatcher->dispatch(new PasswordRequestEvent($entity), PasswordRequestEvent::EVENT);

            $this->addFlash('success', 'E-mail sent.');
        }

        return $this->redirectToRoute('admin_user_edit', [
            'entity' => $entity->getId(),
        ]);
    }

    protected function getConfiguration(): CrudConfiguration
    {
        return CrudConfiguration::create()
            ->setPageTitle('index', 'Users')
            ->setPageTitle('edit', '{username}')
            ->setPageTitle('new', 'New user')
            ->setPageTitle('show', '{username}')

            ->setPageRoute('index', 'admin_user_index')
            ->setPageRoute('new', 'admin_user_new')
            ->setPageRoute('edit', 'admin_user_edit')
            ->setPageRoute('inline_edit', 'admin_user_inline_edit')
            ->setPageRoute('show', 'admin_user_show')
            ->setPageRoute('delete', 'admin_user_delete')
            ->setPageRoute('filter', 'admin_user_filter')

            ->setForm('edit', Type::class, [])
            ->setForm('new', Type::class)

            ->setView('form', '@Core/user/user_admin/_form.html.twig')
            ->setView('index', '@Core/user/user_admin/index.html.twig')
            ->setView('new', '@Core/user/user_admin/new.html.twig')
            ->setView('show', '@Core/user/user_admin/show.html.twig')
            ->setView('show_entity', '@Core/user/user_admin/_show.html.twig')
            ->setView('edit', '@Core/user/user_admin/edit.html.twig')

            ->setDefaultSort('index', 'username')
            ->setDoubleClick('index', true)

            ->setField('index', 'E-mail', Field\TextField::class, [
                'property' => 'email',
                'sort' => ['email', '.email'],
                'attr' => ['class' => 'miw-200'],
            ])
            ->setField('index', 'Display name', Field\TextField::class, [
                'property' => 'displayName',
                'sort' => ['displayName', '.displayName'],
                'attr' => ['class' => 'miw-200'],
                'inline_form' => function (FormBuilderInterface $builder) {
                    $builder->add('displayName', null);
                },
            ])
        ;
    }

    protected function getSection(): string
    {
        return 'user';
    }
}
