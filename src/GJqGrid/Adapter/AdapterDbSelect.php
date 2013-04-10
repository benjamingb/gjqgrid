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

/**
 * @see Gnbit_JqGrid_Adapter_Interface
 */
use Zend\Paginator\Adapter;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Like;
use GJqGrid\Adapter\Sql\Predicate\NotIn;
use GJqGrid\Adapter\Sql\Predicate\NotLike;


class AdapterDbSelect extends DbSelect implements AdapterInterface
{

    public function sort($column = null, $order = "ASC")
    {
        $order = strtoupper($order);
        if (!empty($column)) {
            $this->select->order("$column $order");
        }
    }

    public function filter(array $filters = array())
    {
        $where = $this->select->getRawState('where');

        if (!empty($filters)) {
            foreach ($filters['rules'] as $rules) {

                $predicate = $this->operator($rules, $where);

                if ($filters['groupOp'] == 'AND') {
                    $this->select->where($predicate);
                } else {
                    $this->select->where($predicate->or);
                }
            }
        }
    }
    
    public function getQuery()
    {
       return $this;
    }
    
    /*public function setFilter(array $filters = array())
    {
        if (!empty($filters)) {
            foreach ($filters['rules'] as $value) {

                $parameter = $value['field'] . $this->_getValueOperator($value['op'], $value['data']);
                if (!$this->_isExpression($value['field'])) {
                    if ($filters['groupOp'] == 'AND') {
                        $this->_select->where($parameter);
                    } else {
                        $this->_select->orWhere($parameter);
                    }
                } else {
                    if ($filters['groupOp'] == 'AND') {
                        $this->_select->having($parameter);
                    } else {
                        $this->_select->orHaving($parameter);
                    }
                }
            }
        }
    }*/

    protected function operator($rules, $where)
    {
        $op     = $rules['op'];
        $field  = $rules['field'];
        $data   = $rules['data'];

        switch ($op) {
            case 'eq':
                return $where->equalTo($field, $data);
            case 'ne':
                return $where->notEqualTo($field, $data);
            case 'lt':
                return $where->lessThan($field, $data);
            case 'le':
                return $where->lessThanOrEqualTo($field, $data);
            case 'gt':
                return $where->greaterThan($field, $data);
            case 'ge':
                return $where->greaterThanOrEqualTo($field, $data);
            case 'bw':
                return $where->like($field, "$data%");
            case 'bn':
            	return $where->addPredicate(new NotLike($field, "$data%"));
            case 'in':
            	return $where->in($field, array($data));
            case 'ni':
            	return $where->addPredicate(new NotIn($field, array($data)));
            case 'ew':
                return $where->like($field, "%$data");
            case 'en':
            	return $where->addPredicate(new NotLike($field, "%$data"));
            case 'cn':
                return $where->like($field, "%$data%");
            case 'nc':
            	return $where->addPredicate(new NotLike($field, "%$data%"));
            default:
                return false;
        }
    }

    public function getSelect()
    {
        return $this->select->getSqlString();
    }

}