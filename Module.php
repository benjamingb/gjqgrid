<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/JqGridModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace GJqGrid;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;


use GJqGrid\JqGrid;


class Module implements AutoloaderProviderInterface
{

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ ,
				),
			),
		);
	}

	public function getConfig() 
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function onBootstrap( $e ) 
	{
			JqGrid::setService($e->getApplication()->getServiceManager());

			$moduleConfig = $this->getConfig();  
            $sm = $e->getApplication()->getServiceManager();  
            $helper = $sm->get('viewhelpermanager')->get('jqgrid');  
            $helper->setStylesheets($moduleConfig['GJqGrid']['stylesheets']); 
            $helper->setScripts($moduleConfig['GJqGrid']['scripts']); 
	}
}
