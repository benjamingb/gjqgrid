<?php

namespace GJqGrid;

use GJqGrid\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceManager;



class Source 
{
	


	protected $adapter;



 

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



	public function paginator($page = 1, $rows = 10)
	{
		$paginator = $this->adapter->getPaginator();
		$paginator->setCurrentPageNumber($page, $rows);
		$paginator->setItemCountPerPage($rows);

		return $paginator;
	}

	/*public function getPaginator()
	{
		return $this->paginator;
	}


	public function setJqGridColums($columns)
	{
		$this->jqGridColumns = $columns;
	}*/


	/*public function getData()
	{

        $request = self::getService()->get('request');
		
        $request->getPot('page'); 
        
        //var_dump(self::getService()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('page'));
     
        $paginatorRows =  $this->context->params()->fromQuery('rows',20); 
		$this->setPaginator($paginatorPage, $paginatorRows);
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
     	

        if(!$this->context->getRequest()->isXmlHttpRequest()){
            //return array();
        }

        $viewmodel = new \Zend\View\Model\ViewModel();
        $request = $this->context->getRequest();
        $viewmodel->setTerminal($request->isXmlHttpRequest());
        return $viewmodel;
     	//echo \Zend\Json\Json::encode($rowsetGrid);
     	//return array();
        //return $rowsetGrid;
        
  
	}

	public function context($context)
    {
        $this->context = $context;
        return $this;
    }

    private function request()
    {
        return $this->context;;
    }

    public function toJson()
    {
        $data = $this->getData();
        return  \Zend\Json\Json::encode($data);
    }*/


}