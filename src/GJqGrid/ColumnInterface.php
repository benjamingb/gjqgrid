<?php

/**
 * Gnbit (http://gnbit.com/)
 *
 * @link      http://github.com/benjamingb/GJqGrid
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    Benjamin Gonzales (http://codigolinea.com) 
 */

namespace GJqGrid;

/**
 * @category   Gnbit
 * @package    Zend_Form
 */
interface ColumnInterface
{

    /**
     * Set the name of this column
     *
     * In most cases, this will proxy to the attributes for storage, but is
     * present to indicate that columns are generally named.
     *
     * @param  string $name
     * @return columnInterface
     */
    public function setName($name);

    /**
     * Retrieve the column name
     *
     * @return string
     */
    public function getName();

    /**
     * Set a single column attribute
     *
     * @param  string $key
     * @param  mixed $value
     * @return columnInterface
     */
    public function setAttribute($key, $value);

    /**
     * Retrieve a single column attribute
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * Return true if a specific attribute is set
     *
     * @param  string $key
     * @return bool
     */
    public function hasAttribute($key);

    /**
     * Set many attributes at once
     *
     * Implementation will decide if this will overwrite or merge.
     *
     * @param  array|\Traversable $arrayOrTraversable
     * @return columnInterface
     */
    public function setAttributes($arrayOrTraversable);

    /**
     * Retrieve all attributes at once
     *
     * @return array|\Traversable
     */
    public function getAttributes();

    /**
     * Set the colname (if any) used for this column
     *
     * @param  $colname
     * @return columnInterface
     */
    public function setColName($colname);

    /**
     * Retrieve the colname (if any) used for this column
     *
     * @return string
     */
    public function getColName();

    /**
     * Set a list of messages to report when validation fails
     *
     * @param  array|\Traversable $messages
     * @return columnInterface
     */
    public function setMessages($messages);

    /**
     * Get validation error messages, if any
     *
     * Returns a list of validation failure messages, if any.
     *
     * @return array|\Traversable
     */
    public function getMessages();
}