<?php
return array(
	'GJqGrid'=>array(
		'stylesheets' => array(
			'/js/jqGrid/flick/jquery-ui-1.9.1.custom.min.css',
			'/js/jqGrid/css/ui.jqgrid.css'
		),
		'scripts'=> array(
			'/js/jqGrid/js/i18n/grid.locale-en.js',
			'/js/jqGrid/js/jquery.jqGrid.min.js',
		)
	),
	'view_helpers' => array(
		'invokables' => array(
			  'jqgrid'                  => 'GJqGrid\View\Helper\JqGrid',
			  'column'                  => 'GJqGrid\View\Helper\Column',
		),
	),
);
