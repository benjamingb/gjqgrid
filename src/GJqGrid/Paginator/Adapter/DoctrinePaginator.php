<?php

namespace GJqGrid\Paginator\Adapter;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DPaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class DoctrinePaginator extends DPaginator
{

    public function __construct($query)
    {
        return parent::__construct(new ORMPaginator($query));
    }

}

