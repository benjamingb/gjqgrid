<?php
namespace GJqGrid\Adapter\Sql\Predicate;

use Zend\Db\Sql\Predicate\In;

class NotIn extends In
{

  public function getExpressionData()
    {
        $values = $this->getValueSet();
        if ($values instanceof Select) {
            $specification = '%s NOT IN %s';
            $types = array(self::TYPE_VALUE);
            $values = array($values);
        } else {
            $specification = '%s NOT IN (' . implode(', ', array_fill(0, count($values), '%s')) . ')';
            $types = array_fill(0, count($values), self::TYPE_VALUE);
        }

        $identifier = $this->getIdentifier();
        array_unshift($values, $identifier);
        array_unshift($types, self::TYPE_IDENTIFIER);

        return array(array(
            $specification,
            $values,
            $types,
        ));
    }

}
