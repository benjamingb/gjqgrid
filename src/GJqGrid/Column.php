<?php

/**
 * Gnbit (http://gnbit.com/)
 *
 * @link      http://github.com/benjamingb/JqGridModule
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    Benjamin Gonzales (http://codigolinea.com)
 */

namespace GJqGrid;

use Traversable;
use Zend\Stdlib\ArrayUtils;

class Column implements ColumnInterface {

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * @var string
     */
    protected $colname;

    /**
     * @var array Validation error messages
     */
    protected $messages = array();


    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param  null|int|string  $name    Optional name for the column
     * @param  array            $attributes Optional attributes for the column
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($name = null, $attributes = array())
    {
        if (null !== $name) {
            $this->setName($name);
        }

        if (!empty($attributes)) {
            $this->setAttributes($attributes);
        }
    }

    /**
     * Set value for name
     *
     * @param  string $name
     * @return column|columnInterface
     */
    public function setName($name)
    {
        $this->setAttribute('name', $name);
        return $this;
    }

    public function getName()
    {
        $name = $this->getAttribute('name');
        if(null===$name){
           throw new Exception\InvalidArgumentException(
                            'setName() method expects an string argument');  
           return null;
        }
        return $name;
    }


    /**
     * Get column index
     *
     * @return string
     */
    public function getIndex()
    {
        return null;
        $index = $this->getAttribute('index');
        if (!empty($index)) {
            return $index;
        }

        $index = $this->getName();
        $this->setAttribute('index', $index);

        return $index;
    }


    /**
     * Set a single column attribute
     *
     * @param  string $key
     * @param  mixed  $value
     * @return column|columnInterface
     */
    public function setAttribute($key, $value)
    {
  
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Retrieve a single column attribute
     *
     * @param  $key
     * @return mixed|null
     */
    public function getAttribute($key)
    {
        $this->getIndex();
        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }
        return $this->attributes[$key];
    }

    /**
     * Does the column has a specific attribute ?
     *
     * @param  string $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Set many attributes at once
     *
     * Implementation will decide if this will overwrite or merge.
     *
     * @param  array|Traversable $arrayOrTraversable
     * @return column|columnInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setAttributes($arrayOrTraversable)
    {
        if (!is_array($arrayOrTraversable) && !$arrayOrTraversable instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                            '%s expects an array or Traversable argument; received "%s"', 
                            __METHOD__, 
                            (is_object($arrayOrTraversable) ? get_class($arrayOrTraversable) : gettype($arrayOrTraversable))
            ));
        }

        foreach ($arrayOrTraversable as $key => $value) {
            $this->setAttribute($key, $value);
        }
        
        $this->getIndex();
        
        return $this;
    }

    /**
     * Retrieve all attributes at once
     *
     * @return array|Traversable
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Clear all attributes
     *
     * @return column|columnInterface
     */
    public function clearAttributes()
    {
        $this->attributes = array();
        return $this;
    }

  /**
     * Remove attribute
     *
     * @param  string $key
     * @return bool
     */
    public function removeAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
            return true;
        }
        return false;
    }
    
    /**
     * Set the colname used for this column
     *
     * @param $colname
     * @return column|columnInterface
     */
    public function setColName($colname)
    {
        if (is_string($colname)) {
            $this->colname = $colname;
        }

        return $this;
    }

    /**
     * Retrieve the colname used for this column
     *
     * @return string
     */
    public function getColName()
    {
        if (!empty($this->colname)) {
            return $this->colname ;
        }
         $colname = $this->getName();
         $this->setColName($colname);
         return $this->colname;
    }


     /* Set a list of messages to report when validation fails
     *
     * @param  array|Traversable $messages
     * @return column|columnInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setMessages($messages)
    {
        if (!is_array($messages) && !$messages instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                            '%s expects an array or Traversable object of validation error messages; received "%s"', __METHOD__, (is_object($messages) ? get_class($messages) : gettype($messages))
            ));
        }

        $this->messages = $messages;
        return $this;
    }

    /**
     * Get validation error messages, if any.
     *
     * Returns a list of validation failure messages, if any.
     *
     * @return array|Traversable
     */
    public function getMessages()
    {
        return $this->messages;
    }

}