<?php

namespace GJqGrid\Adapter\Paginator;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class GDoctrinePaginator extends DoctrinePaginator
{

    public function __construct($query)
    {
        return parent::__construct(new ORMPaginator($query));
    }

}

