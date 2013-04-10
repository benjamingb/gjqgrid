<?php

return array(
    'GJqGrid' => array(
        'stylesheets' => array(
            '/js/jqGrid/flick/jquery-ui-1.10.2.custom.min.css',
            '/js/jqGrid/css/ui.jqgrid.css',
            '/js/jqGrid/plugins/ui.multiselect.css',
        ),
        'scripts' => array(
            '/js/jqGrid/js/jquery-ui-1.10.2.custom.min.js',
            '/js/jqGrid/js/i18n/grid.locale-es.js',
            '/js/jqGrid/plugins/ui.multiselect.js',
            '/js/jqGrid/js/jquery.jqGrid.min.js',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'jqgrid' => 'GJqGrid\View\Helper\JqGrid',
            'column' => 'GJqGrid\View\Helper\Column',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
