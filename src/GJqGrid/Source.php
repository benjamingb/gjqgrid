<?php

namespace GJqGrid;

use GJqGrid\Adapter\AdapterInterface;




class Source 
{
	


	protected $adapter;

	protected $paginator;

	protected $jqGridColumns = array();

	function __construct($adapter)
	{
		if(!$adapter instanceof AdapterInterface)
		{
			throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that $adapter be an object implementing %s; received "%s"',
                __METHOD__,
                __NAMESPACE__ . '\AdapterInterface',
                (is_object($adapter) ? get_class($adapter) : gettype($adapter))
            ));
		}
		$this->adapter = $adapter;
	}


	public function setPaginator($page = 1, $rows = 10)
	{
		$this->paginator = $this->adapter->getPaginator();
		$this->paginator->setCurrentPageNumber($page, $rows);
		$this->paginator->setItemCountPerPage($rows);

		return $this;
	}

	public function getPaginator()
	{
		return $this->paginator;
	}


	public function setJqGridColums($columns)
	{
		$this->jqGridColumns = $columns;
	}


	public function getData()
	{

		$this->setPaginator();
		$paginator = $this->getPaginator();

		$rowsetGrid = array();
		$rowsetGrid['page'] = $paginator->getCurrentPageNumber();
        $rowsetGrid['total'] = $paginator->getPages()->pageCount;
        $rowsetGrid['records'] = $paginator->getTotalItemCount();

        $items = $paginator->getCurrentItems();
        $jqGridColumns = $this->jqGridColumns;

        foreach ($items as $index => $column) {
        	$cells = array();
        	 if (is_array($column)) {
        	 	foreach ($jqGridColumns as $jqCol) {
        	 		$name = $jqCol->getName();
        			if (isset($column[$name])) {
                        $cells[] = $column[$name]; 
                    } else {
                        $cells[] = ''; //columna Vacia 
                    }
                    $rowsetGrid['rows'][$index]['id'] =  $cells[0];
        		}
        	}else {
                $cells[] = $column;
                $rowsetGrid['rows'][$index]['id'] = $index;
            }
            $rowsetGrid['rows'][$index]['cell'] = $cells;	
        	
        }

        return $rowsetGrid;
		//var_dump();
		//print_r($rowsetGrid);

       //cho  \Zend\Json\Json::encode($rowsetGrid);
		//var_dump($this->jqGridColumns);

        /*foreach ($paginator as $row => $column) 
        {
        	var_dump($row);
        	var_dump($column);
        }*/
	}
}