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
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;
use GJqGrid\Paginator\Adapter\DoctrinePaginator;

/**
 * Nota:
 * 
 * Tiene muchas limitaciones 
 *- No funciona La pagina con Consultas cimpuestas (Join)
 *- Falta definir el alias, cuando sean sonsultas compuestas   
 */

class AdapterDoctrine implements AdapterInterface
{

    protected $entityManager;
    protected $query = null;

    public function __construct($query, $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->query = $query;
    }

    public function sort($column = null, $order = "ASC")
    {
        $order = strtoupper($order);
        if (!empty($column)) {
            $alias = $this->query->getRootAliases();
            $this->query->orderBy("{$alias[0]}.$column", "$order");
        }
    }

    public function filter(array $filters = array())
    {

        $alias = $this->query->getRootAliases();

        $qb = $this->entityManager->createQueryBuilder();

        if (!empty($filters)) {
            foreach ($filters['rules'] as $rules) {
                $predicate = $this->operator($rules, $qb);
                if ($filters['groupOp'] == 'AND') {
                    $this->query->andWhere($predicate);
                } else {
                    $this->query->orWhere($predicate);
                }
            }
        }
    }

    protected function operator($rules, $qb)
    {
        $op = $rules['op'];
        $field = "i." . $rules['field'];
        $data = $rules['data'];

        switch ($op) {
            case 'eq':
                return $qb->expr()->eq($field, $qb->expr()->literal($data));
            case 'ne':
                return $qb->expr()->neq($field, $qb->expr()->literal($data));
            case 'lt':
                return $qb->expr()->lt($field, $qb->expr()->literal($data));
            case 'le':
                return $qb->expr()->lte($field, $qb->expr()->literal($data));
            case 'gt':
                return $qb->expr()->gt($field, $qb->expr()->literal($data));
            case 'ge':
                return $qb->expr()->gte($field, $qb->expr()->literal($data));
            case 'bw':
                return $qb->expr()->like($field, $qb->expr()->literal($data . '%'));
            case 'bn':
                return $qb->expr()->not($qb->expr()->like($field, $qb->expr()->literal($data . '%')));
            case 'ew':
                return $qb->expr()->like($field, $qb->expr()->literal('%' . $data));
            case 'en':
                return $qb->expr()->not($qb->expr()->like($field, $qb->expr()->literal('%' . $data)));
            case 'cn':
                return $qb->expr()->like($field, $qb->expr()->literal('%' . $data . '%'));
            case 'nc':
                return $qb->expr()->not($qb->expr()->like($field, $qb->expr()->literal('%' . $data . '%')));
            default:
                return false;
        }
    }

    public function getQuery()
    {
        $paginator = new GDoctrinePaginator($this->query->setHydrationMode(2)); //Array
        return $paginator;
    }

}