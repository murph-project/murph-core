<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Core\Controller\Admin\Crud\CrudController;
use App\Core\Crud\CrudConfiguration;
use App\Core\Crud\Field;
use App\Core\Entity\EntityInterface;
use App\Core\Manager\EntityManager;
use <?php echo $entity; ?> as Entity;
use <?php echo $factory; ?> as Factory;
use <?php echo $form; ?> as Type;
use <?php echo $repository_query; ?> as RepositoryQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class <?php echo $class_name; ?> extends CrudController
{
    protected ?CrudConfiguration $configuration = null;

    #[Route(path: '/admin/<?php echo $route; ?>/{page}', name: 'admin_<?php echo $route; ?>_index', methods: ['GET'], requirements: ['page' => '\d+'])]
    public function index(RepositoryQuery $query, Request $request, Session $session, int $page = 1): Response
    {
        return $this->doIndex($page, $query, $request, $session);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/new', name: 'admin_<?php echo $route; ?>_new', methods: ['GET', 'POST'])]
    public function new(Factory $factory, EntityManager $entityManager, Request $request): Response
    {
        return $this->doNew($factory->create(), $entityManager, $request);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/show/{entity}', name: 'admin_<?php echo $route; ?>_show', methods: ['GET'])]
    #[IsGranted('show', 'entity')]
    public function show(Entity $entity): Response
    {
        return $this->doShow($entity);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/filter', name: 'admin_<?php echo $route; ?>_filter', methods: ['GET'])]
    public function filter(Session $session): Response
    {
        return $this->doFilter($session);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/edit/{entity}', name: 'admin_<?php echo $route; ?>_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'entity')]
    public function edit(Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        return $this->doEdit($entity, $entityManager, $request);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/inline_edit/{entity}/{context}/{label}', name: 'admin_<?php echo $route; ?>_inline_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'entity')]
    public function inlineEdit(string $context, string $label, Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        return $this->doInlineEdit($context, $label, $entity, $entityManager, $request);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/sort/{page}', name: 'admin_<?php echo $route; ?>_sort', methods: ['POST'], requirements: ['page' => '\d+'])]
    public function sort(RepositoryQuery $query, EntityManager $entityManager, Request $request, Session $session, int $page = 1): Response
    {
        return $this->doSort($page, $query, $entityManager, $request, $session);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/batch/{page}', name: 'admin_<?php echo $route; ?>_batch', methods: ['POST'], requirements: ['page' => '\d+'])]
    public function batch(RepositoryQuery $query, EntityManager $entityManager, Request $request, Session $session, int $page = 1): Response
    {
        return $this->doBatch($page, $query, $entityManager, $request, $session);
    }

    #[Route(path: '/admin/<?php echo $route; ?>/delete/{entity}', name: 'admin_<?php echo $route; ?>_delete', methods: ['DELETE', 'POST'])]
    #[IsGranted('delete', 'entity')]
    public function delete(Entity $entity, EntityManager $entityManager, Request $request): Response
    {
        return $this->doDelete($entity, $entityManager, $request);
    }

    protected function getConfiguration(): CrudConfiguration
    {
        if ($this->configuration) {
            return $this->configuration;
        }

        return $this->configuration = CrudConfiguration::create()
            ->setPageTitle('index', 'List of <?php echo $entity; ?>')
            ->setPageTitle('edit', 'Edition of {id}')
            ->setPageTitle('new', 'New <?php echo $entity; ?>')
            ->setPageTitle('show', 'View of {id}')

            ->setPageRoute('index', 'admin_<?php echo $route; ?>_index')
            ->setPageRoute('new', 'admin_<?php echo $route; ?>_new')
            ->setPageRoute('edit', 'admin_<?php echo $route; ?>_edit')
            ->setPageRoute('inline_edit', 'admin_<?php echo $route; ?>_inline_edit')
            ->setPageRoute('show', 'admin_<?php echo $route; ?>_show')
            ->setPageRoute('sort', 'admin_<?php echo $route; ?>_sort')
            ->setPageRoute('batch', 'admin_<?php echo $route; ?>_batch')
            ->setPageRoute('delete', 'admin_<?php echo $route; ?>_delete')
            ->setPageRoute('filter', 'admin_<?php echo $route; ?>_filter')

            ->setForm('edit', Type::class)
            ->setForm('new', Type::class)

            ->setView('form', 'admin/<?php echo $route; ?>_admin/_form.html.twig')
            ->setView('show_entity', 'admin/<?php echo $route; ?>_admin/_show.html.twig')

            // ->setForm('filter', Type::class)

            // ->setMaxPerPage('index', 20)

            // ->setIsSortableCollection('index', false)
            // ->setSortableCollectionProperty('sortOrder')

            // ->setAction('index', 'new', true)
            // ->setAction('index', 'show', true)
            // ->setAction('index', 'edit', true)
            // ->setAction('index', 'delete', true)
            // ->setDoubleClick('index', false)

            // ->setAction('edit', 'back', true)
            // ->setAction('edit', 'show', true)
            // ->setAction('edit', 'delete', true)

            // ->setAction('show', 'back', true)
            // ->setAction('show', 'edit', true)

            ->setField('index', 'Entity', Field\TextField::class, [
                'property_builder' => function (EntityInterface $entity) {
                    try {
                        return (string) $entity;
                    } catch (\Error $e) {
                        return $entity->getId();
                    }
                },
            ])

            // ->setField('index', 'Foo', Field\TextField::class, [
            //     'property' => 'foo',
            // ])

            // ->setBatchAction('index', 'delete', 'Delete', function(EntityInterface $entity, EntityManager $manager) {
            //     $manager->delete($entity);
            // })
        ;
    }

    protected function getSection(): string
    {
        return '<?php echo $route; ?>';
    }
}
