<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Core\Repository\RepositoryQuery;
use Knp\Component\Pager\PaginatorInterface;
use <?php echo $repository; ?> as Repository;

class <?php echo $class_name; ?> extends RepositoryQuery
{
    public function __construct(Repository $repository, PaginatorInterface $paginator)
    {
        parent::__construct($repository, '<?php echo $id; ?>', $paginator);
    }
}
