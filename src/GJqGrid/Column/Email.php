<?php

namespace GJqGrid\Column;

use GJqGrid\Column;

class Email extends Column
{

    protected $attributes = array(
        'formatter' => 'email',
    );
}