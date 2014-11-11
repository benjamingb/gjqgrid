<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace GJqGrid;

use Traversable;
use GJqGrid\Exception;
use GJqGrid\Source;
use Zend\ServiceManager\ServiceManager;
use Zend\Json\Json;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Paginator;

/**
 * @category   Zend
 * @package    Zend_Form
 */
class JqGrid implements JqGridInterface
{

    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'mtype' => 'POST',
        'url' => '',
        'datatype' => 'local',
        'colNames' => '',
        'colModel' => '',
        'width' => 'auto',
        'height' => 'auto',
    );
    protected $methods = array();

    /**
     * id jqgrid
     * 
     * @var string
     */
    protected $id;
    protected $columns = array();
    protected $source;
    protected static $serviceManager;
    protected $model = null;

    /**
     * @param  int|string  $id  for the jqgrid
     * @param  array       $attributes Optional attributes for the jqgrid
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($id = null, $attributes = array())
    {
        if (null !== $id) {
            $this->setId($id);
        }

        if (!empty($attributes)) {
            $this->setAttributes($attributes);
        }

        $this->init();
    }

    public function init()
    {
        //imppelments
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        if (empty($this->id)) {
            throw new Exception\InvalidArgumentException('JqGrid require have a id ' . __METHOD__);
        }
        return $this->id;
    }

    /**
     * Set a single grid attribute
     *
     * @param  string $key
     * @param  mixed  $value
     * @return grid|gridInterface
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Retrieve a single grid attribute
     *
     * @param  $key
     * @return mixed|null
     */
    public function getAttribute($key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }
        return $this->attributes[$key];
    }

    /**
     * Does the grid has a specific attribute ?
     *
     * @param  string $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Set many attributes at once
     *
     * Implementation will decide if this will overwrite or merge.
     *
     * @param  array|Traversable $arrayOrTraversable
     * @return grid|gridInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setAttributes($arrayOrTraversable)
    {
        if (!is_array($arrayOrTraversable) && !$arrayOrTraversable instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                    '%s expects an array or Traversable argument; received "%s"', __METHOD__, (is_object($arrayOrTraversable) ? get_class($arrayOrTraversable) : gettype($arrayOrTraversable))
            ));
        }

        foreach ($arrayOrTraversable as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    /**
     * Retrieve all attributes at once
     *
     * @return array|Traversable
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Clear all attributes
     *
     * @return grid|gridInterface
     */
    public function clearAttributes()
    {
        $this->attributes = array();
        return $this;
    }

    /**
     * Remove attribute
     *
     * @param  string $key
     * @return bool
     */
    public function removeAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
            return true;
        }
        return false;
    }

    public function setMethod()
    {
        $numargs = func_num_args();
        $arguments = func_get_args();

        if ($numargs > 0) {
            $key = (string) $arguments[0];
            unset($arguments[0]);
            if (in_array($key, array('navGrid', 'navButtonAdd'))) {
                if (is_string($arguments[1]) && "#" == $arguments[1][0]) {
                    $this->setAttribute('pager', $arguments[1]);
                } else {
                    $this->setAttribute('pager', "{$this->getId()}_pager");
                    array_unshift($arguments, "#{$this->getId()}_pager");
                }
            }

            $this->methods[$key] = $arguments;
        }
        return $this;
    }

    public function getDataType()
    {
        if (null === $this->getAttribute('datatype')) {
            $this->setAttribute('datatype', 'local');
        }

        return $this->getAttribute('datatype');
    }

    /**
     * 
     * returnarray()
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     *
     * @param Gnbit_JqGrid_Column $column
     * @param type $name
     * @return Gnbit_JqGrid 
     */
    public function addColumn($column, $name = null, $options = array())
    {
        if (is_string($column)) {
            if (null === $name) {
                throw new Exception\InvalidArgumentException('Column specified by string must have an accompanying name');
            }
            $class = '\GJqGrid\Column\\' . ucfirst(strtolower($column));
            $this->columns[$name] = new $class($name, $options);
        } elseif ($columnType instanceof ColumnInterface) {
            $name = $column->getName();
            $this->columns[$name] = $column;
        }
    }

    /**
     * Retrieve a single column
     *
     * @param  string $name
     * @return Jqgrid_Column|null
     */
    public function getColumn($name)
    {
        if (array_key_exists($name, $this->columns)) {
            return $this->columns[$name];
        }
        return null;
    }

    /**
     * Retrieve all $this->columns;     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Remove Column
     *
     * @param  string $name
     * @return boolean
     */
    public function removeColumn($name)
    {
        $name = (string) $name;
        if (isset($this->columns[$name])) {
            unset($this->columns[$name]);
            return true;
        }
        return false;
    }

    public static function setService(ServiceManager $sm)
    {
        self::$serviceManager = $sm;
    }

    public static function getService()
    {
        return self::$serviceManager;
    }

    public function source($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    private function getParam($name, $default = null)
    {
        $mtype = $this->getAttribute('mtype');
        $request = self::getService()->get('request');
        if (null !== $mtype && strtoupper($mtype) == 'POST') {
            return $request->getPost($name, $default);
        } else {
            $param = $request->getQuery($name, $default);
            return $param;
        }
    }

    public function display()
    {

        if (!self::getService()->get('request')->isXmlHttpRequest()) {
            return null;
        }

        $operation = $this->getParam('oper');
        if ($operation) {
            $this->operations($operation);
            return null;
        }


        $page = (int) $this->getParam('page', 1); //page number
        $rows = (int) $this->getParam('rows', 20); //rows for page


        $source = $this->getSource();
        //$paginator = new Paginator($this->getSource());
        // Filter items 
        if ($this->getParam('_search') == 'true') {
            $filters = Json::decode($this->getParam('filters'), Json::TYPE_ARRAY);
            $source->filter($filters);
        }
        //Sort items by column
        if ($sidx = $this->getParam('sidx')) {
            $source->sort($sidx, $this->getParam('sord'));
        }


        $paginator = new Paginator($source->getQuery());
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($rows);
       

        $rowsetGrid = array();
        $rowsetGrid['page'] = $paginator->getCurrentPageNumber();
        $rowsetGrid['total'] = $paginator->count();
        $rowsetGrid['records'] = $paginator->getTotalItemCount();


        $items = $paginator->getCurrentItems();
        $jqGridColumns = $this->getColumns();

        foreach ($items as $index => $column) {
            $cells = array();
            if (is_array($column) || is_object($column)) {
                foreach ($jqGridColumns as $jqCol) {
                    $name = $jqCol->getName();
                    if (isset($column[$name])) {
                        $cells[] = $column[$name];
                    } else {
                        $cells[] = ''; //empty column
                    }
                    $rowsetGrid['rows'][$index]['id'] = $cells[0];
                }
            } else {
                $cells[] = $column;
                $rowsetGrid['rows'][$index]['id'] = $index;
            }
            $rowsetGrid['rows'][$index]['cell'] = $cells;
        }


        $this->sendResponse(\Zend\Json\Json::encode($rowsetGrid));
    }

    public function model($model)
    {
        $editUrl = $this->getAttribute('editurl');
        if (empty($editUrl)) {
            $request = self::getService()->get('request');
            $uri = $request->getUri();
            $this->setAttribute('editurl', $uri->getPath());
        }
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    protected function operations($type)
    {
        if (!$this->getModel()) {
            return false;
        }
        $request = self::getService()->get('request');
        if ($type == 'del') {
            $this->getModel()->remove($request->getPost());
        } else {
            $this->getModel()->persist($request->getPost());
        }
        exit;
    }

    protected function sendResponse($data)
    {
        echo $data;
        exit;
    }

}