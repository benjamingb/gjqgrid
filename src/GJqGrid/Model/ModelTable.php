<?php

namespace GJqGrid\Model;

use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use GJqGrid\Exception;

class ModelTable extends AbstractTableGateway
{

    protected $id = 0;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function table($table)
    {
        $this->table = $table;
        $this->initialize();
        return $this;
    }

    public function id($id)
    {
        $this->id = trim($id);
    }

    /* public function getMeta()
      {
      if (!isset($this->meta['table']) && !isset($this->meta['id'])) {
      throw new Exception\InvalidArgumentException('option "table" and "id" is not defined in meta');
      return false;
      }
      return $this->meta;
      } */

    public function persist($data)
    {
        $hydrator = new ArraySerializable();
        $list = $hydrator->extract($data);

        $id = isset($list[$this->id]) ? $list[$this->id] : (isset($list['id']) ? $list['id'] : null);

        unset($list[$this->id]);
        unset($list['id']);
        unset($list['oper']);

        if ($id > 0) {
            $this->update($list, array($this->id => $id));
        } else {
            $this->insert($list);
        }
    }

    public function remove($data)
    {
        // $this->delete(array($this->id => $data->id));
    }

}