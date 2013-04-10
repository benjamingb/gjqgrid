<?php

namespace GJqGrid\Column;

use GJqGrid\Column;

class Actions extends Column
{

    CONST AFTER_SAVE = <<<FUNCTION
       function(rowid) {
             $(this).trigger('reloadGrid')
        }
FUNCTION;

    protected $attributes = array(
        'name' => 'actions',
        'index' => 'actions',
        'width' => '50',
        'search' => false,
        'fixed' => true,
        'sortable' => false,
        'resizable' => false,
        'frozen' => true,
        'formatter' => 'actions',
    );
    protected $colname = ' ';

    public function __construct($name = null, array $attributes = array())
    {
        parent::__construct($name, $attributes);
        $this->setAttribute('formatoptions', array(
            'key' => true,
            'afterSave' => new \Zend\Json\Expr($this::AFTER_SAVE),
        ));
    }

}