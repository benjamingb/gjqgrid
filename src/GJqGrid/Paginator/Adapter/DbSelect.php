<?php

namespace GJqGrid\Paginator\Adapter;

use Zend\Paginator\Adapter\DbSelect as PaginatorDbSelect;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;


/**
 * @category   Zend
 * @package    Zend_Paginator
 */
class DbSelect extends PaginatorDbSelect
{

    public function count()
    {
        if ($this->rowCount !== null) {
            return $this->rowCount;
        }

        $select = clone $this->select;

        $columnParts = $this->select->getRawState('columns');
        $groupParts = $this->select->getRawState('group');
        $havingParts = $this->select->getRawState('having');

        $select->reset(Select::LIMIT);
        $select->reset(Select::OFFSET);
        $select->reset(Select::ORDER);

        $selecCount = new Select();
        $selecCount->from(array('t' => $select))
                ->columns(array('c' => new Expression('COUNT(1)')));

        $statement = $this->sql->prepareStatementForSqlObject($selecCount);
        $result = $statement->execute();
        $row = $result->current();
        $this->rowCount = $row['c'];
        return $this->rowCount;
    }

}