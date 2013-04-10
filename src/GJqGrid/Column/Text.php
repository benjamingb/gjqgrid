<?php

namespace GJqGrid\Column;

use GJqGrid\Column;

class Text extends Column
{

    protected $attributes = array(
        'align' => 'left',
        'searchoptions' => array('sopt' => array('cn'))
    );

}