<?php

namespace GJqGrid\Adapter;

/**
 * GJqGrid
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to benjamin@gnbit.com so we can send you a copy immediately.
 * 
 * @category   GJqGrid
 * @package    Gnbit_JqGrid
 * @copyright  Copyright (c) 2011 GnBit.SAC. (http://www.gnbit.com)
 * @license    http://gnbit.com/license/new-bsd     New BSD License
 * @version    $
 */
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;

class AdapterDoctrine extends DoctrinePaginator implements AdapterInterface
{

    protected $entityManager;
    protected $select = null;
    protected $adpater;

    public function __construct($query, $entityManager)
    {
        //$query->orderBy('r.e', 'DESC');
        $this->select = $query; //Array
        parent::__construct(new ORMPaginator($this->select->getQuery()->setHydrationMode(2)));
        //$this->entityManager = $entityManager;
    }

    public function sort($column = null, $order = "ASC")
    {
        $order = strtoupper($order);
        if (!empty($column)) {
            $alias = $this->select->getRootAliases();
            $this->select->orderBy("{$alias[0]}.$column", "$order");
        }
    }

    public function filter(array $filters = array())
    {
        
    }

}