<?php

namespace GJqGrid\Column;

use GJqGrid\Column;

class Integer extends Column
{

    protected $attributes = array(
        'align' => 'right',
        'formatter' => 'integer',
        'formatoptions' => array('thousandsSeparator' => ''),
    );
}