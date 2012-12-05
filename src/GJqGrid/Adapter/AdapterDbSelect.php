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
        if (!empty($filters)) {
            foreach ($filters['rules'] as $rules) {

                $where = $this->operator($rules['op'], $rules['field'], $rules['data']);

                if ($filters['groupOp'] == 'AND') {
                    $this->select->where($where);
                } else {
                    $this->select->orWhere($where);
                }
            }
        }
    }

    protected function operator($op, $field, $data)
    {
        $where = new Where ();

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
            //return $db->quoteInto(" NOT LIKE ?", "$value%");
            case 'in':
            //return $db->quoteInto(" IN (?)", $value);
            case 'ni':
            //return $db->quoteInto(" NOT IN (?)", $value);
            case 'ew':
                return $where->like($field, "%$data");
            case 'en':
            //return $db->quoteInto(" NOT LIKE ?", "%$value");
            case 'cn':
                return $where->like($field, "%$data%");
            case 'nc':
            //return $db->quoteInto(" NOT LIKE ?", "%$value%");
            default:
                return false;
        }
    }

}