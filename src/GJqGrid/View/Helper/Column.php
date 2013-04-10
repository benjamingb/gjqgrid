<?php
namespace GJqGrid\View\Helper;


use Zend\I18n\View\Helper\AbstractTranslatorHelper as BaseAbstractHelper;
use GJqgrid\JqGridInterface;
/**
* 
*/
class Column extends BaseAbstractHelper
{
    public function render(JqGridInterface $jqgrid)
    {
    	$colNames = array();
    	$colModel = array();
        if ($jqgrid instanceof JqGridInterface) {
            $cols = $jqgrid->getColumns();
            foreach ($cols as $col) {
            	$colNames[] = $col->getColName();
            	$colModel[] = $col->getAttributes();
            }
        }
        $jqgrid->setAttribute('colNames',$colNames);
        $jqgrid->setAttribute('colModel',$colModel);
        return  $jqgrid;
    }
}