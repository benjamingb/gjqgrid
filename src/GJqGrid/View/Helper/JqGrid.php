<?php
#https://bitbucket.org/odiaseo/data-grid-for-zend-framework-2/src/459f34afec58?at=master
namespace GJqGrid\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper as BaseAbstractHelper;
use Zend\Json\Json;
use GJqgrid\JqGridInterface;

/**
 *
 */
class JqGrid extends BaseAbstractHelper
{

    private $stylesheets    = array();
    private $scripts        = array();
    private $jqGridObject   = null;
    private $jqGridId       = null;
    private $jqGridAttr     = null;

    /**
     * Invoke as function
     *
     * @return Form
     */
    public function __invoke()
    {
        return $this;
    }

    public function setStylesheets(array $stylesheets = array())
    {
        $this->stylesheets = $stylesheets;
    }

    public function setScripts(array $scripts = array())
    {
        $this->scripts = $scripts;
    }

    public function init(JqGridInterface $jqgrid = null)
    {
        if ($jqgrid instanceof JqGridInterface) {
            $this->jqGridObject = $jqgrid;
            $this->jqGridId     = $jqgrid->getId();
            $this->jqGridAttr   = $jqgrid->getAttributes();
        }
    }


    public function prepareColumns()
    {
        
    }

    public function methods()
    {
        $func = function($value) {
                    return Json::encode($value, false, array('enableJsonExprFinder' => true));
                };

        $methods = $this->jqGridObject->getMethods();
        $script = null;
        foreach ($methods as $key => $value) {
            $attrs = null;
            if (!empty($value)) {
                $attrs = "," . implode(",", array_map($func, $value));
            }
            $script .= sprintf('jQuery("#%s").jqGrid("' . $key . '"%s);', $this->jqGridId, $attrs) . PHP_EOL;
        }
        return $script;
    }

    /**
     * Generate an opening form tag
     *
     * @param  null|FormInterface $form
     * @return string
     */
    public function headScripts()
    {
        if (!empty($this->stylesheets)) {
            foreach ($this->stylesheets as $style) {
                $this->view->headLink()->appendStylesheet($this->view->basePath() . $style);
            }
        }
        if (!empty($this->stylesheets)) {
            foreach ($this->scripts as $scripts) {
                $this->view->headScript()->appendFile($this->view->basePath() . $scripts);
            }
        }
    }

    public function container(JqGridInterface $grid = null)
    {
        $html = null;
        if (null !== $this->jqGridObject) {
            $pager = $this->jqGridObject->getAttribute('pager');
            $html .= "<table id='{$this->jqGridId}'></table>" . PHP_EOL;
            $html .=!empty($pager) ? "<div id='$pager'></div>" . PHP_EOL : null;
        }
        return $html;
    }

    public function grid()
    {
        $script = null;
        if (null !== $this->jqGridObject) {
            $this->jqGridObject = $this->view->column()->render($this->jqGridObject);
            $json = Json::encode($this->jqGridObject->getAttributes(), false, array('enableJsonExprFinder' => true));
            $script = sprintf('jQuery("#%s").jqGrid(%s);', $this->jqGridId, $json);
            $script .= $this->methods();
        }
        //return $script;
        return Json::prettyPrint($script, array("indent" => " "));
    }

    public function displayAll()
    {
        $this->headScripts();
        $this->view->inlineScript()->appendScript($this->grid());
        $display = $this->container();
        return $display;
    }

}
