<?php

namespace GJqGrid\Adapter;


interface  AdapterInterface
{
	/**
     * Sort records
     *
     */
    public function sort($column, $order);

    /**
     * Filter records
     *
    */
    public function filter(array $filters = array());
}