<?php

namespace App\Core\Repository;

use App\Core\Repository\RedirectRepository as Repository;
use Knp\Component\Pager\PaginatorInterface;

class RedirectRepositoryQuery extends RepositoryQuery
{
    public function __construct(Repository $repository, PaginatorInterface $paginator)
    {
        parent::__construct($repository, 'r', $paginator);
    }
}
