<?php

/**
 * Gnbit
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to benjamin@gnbit.com so we can send you a copy immediately.
 * 
 * @category   Gnbit
 * @package    Gnbit_JqGrid
 * @copyright  Copyright (c) 2011 GnBit.SAC. (http://www.gnbit.com)
 * @license    http://gnbit.com/license/new-bsd     New BSD License
 * @version    $
 */
/**
 * @see Gnbit_JqGrid_Adapter_Interface
 */

namespace GJqGrid\Adapter;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use Zend\Paginator\Paginator;


class DoctrineArrayAdapter implements AdapterInterface
{

    const TYPE = 'Array';

    /**
     *
     * @var type 
     */
    protected $array = null;

    /**
     *
     * @var type 
     */
    protected $_newArray = null;

    protected $paginator = null;

    /**
     *
     * @param array $array 
     */
    public function __construct(Array $array)
    {
        //$this->array = $array;
        //$this->_newArray = $this->array;
       $doctrineAdapter =  new CollectionAdapter(new ArrayCollection($array));
       $this->paginator  = new Paginator ($doctrineAdapter);
    }

    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Order Data
     * 
     * @param type $column
     * @param type $order 
     */
    public function setSort($column = null, $order = "ASC")
    {
        if (isarray($this->_newArray)) {
            if ($column !== null && strlen(trim($column)) > 0) {
                $data = $this->_orderBy($this->_newArray, $column);
                if (strtoupper($order) == "DESC") {
                    $data = array_reverse($data);
                } else {
                    sort($this->_newArray);
                }
                $this->_newArray = $data;
            }
        }
    }

    /**
     * Filter Data 
     * 
     * @param array $filters 
     */
    public function setFilter(array $filters = array())
    {
        if (!empty($filters)) {
            $newArray = array();
            foreach ($this->array as $row) {
                $countTrue = 0;
                $countFalse = 0;
                foreach ($filters['rules'] as $value) {
                    $validator = $this->_validator($value['op'], $row[$value['field']], $value['data']);
                    if ($validator == true) {
                        $countTrue++;
                    } else {
                        $countFalse++;
                    }
                }
                if (($filters['groupOp'] == 'AND' && !$countFalse) || ($filters['groupOp'] == 'OR' && $countTrue)) {
                    $newArray[] = $row;
                }
            }
            $this->_newArray = $newArray;
        }
    }

    /**
     *
     * @return type 
     */
    public function assembly()
    {
        return $this->_newArray;
    }

    /**
     *
     * @param type $operator
     * @param type $valueField
     * @param type $valueFilter
     * @return type 
     */
    protected function _validator($operator, $valueField, $valueFilter)
    {
        switch ($operator) {
            case 'eq':
            case 'in':
                return ($valueField === $valueFilter) ? true : false;
            case 'ne':
            case 'ni':
                return ($valueField !== $valueFilter) ? true : false;
            case 'lt':
                return ($valueField < $valueFilter) ? true : false;
            case 'le':
                return ($valueField <= $valueFilter) ? true : false;
            case 'gt':
                return ($valueField > $valueFilter) ? true : false;
            case 'ge':
                return ($valueField >= $valueFilter) ? true : false;
            case 'bw':
                $aux = substr($valueField, 0, strlen($valueFilter));
                return ($aux === $valueFilter) ? true : false;
            case 'bn':
                $aux = substr($valueField, 0, strlen($valueFilter));
                return ($aux !== $valueFilter) ? true : false;
            case 'ew':
                $aux = substr($valueField, strlen($valueField) - strlen($valueFilter));
                return ($aux === $valueFilter) ? true : false;
            case 'en':
                $aux = substr($valueField, strlen($valueField) - strlen($valueFilter));
                return ($aux !== $valueFilter) ? true : false;
            case 'cn':
                return (strpos($valueFilter, $valueField) !== false) ? true : false;
            case 'nc':
                return (strpos($valueFilter, $valueField) === false) ? true : false;
            default:
                return false;
        }
    }

    /**
     *
     * @param type $array
     * @param type $column
     * @return type 
     */
    protected function _orderBy($array, $column)
    {
        $code = "return strnatcmp(\$a['$column'], \$b['$column']);";
        usort($array, create_function('$a,$b', $code));
        return $array;
    }

    /**
     * Only versions PHP5.2X
     * 
     * @param type $name
     * @return type 
     */
    public function __get($name)
    {
        if (defined("self::$name")) {
            return constant("self::$name");
        }
        trigger_error("$name  isn't defined");
    }

}