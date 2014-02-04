<?php

class DIContainer implements ArrayAccess
{
    private $values = array();
    private $raw = array();
    private $keys = array();

    /**
     * Sets a parameter or an object.
     * Objects must be defined as Closures.
     *
     * @param string $id    The unique identifier for the parameter or object
     * @param mixed  $value The value of the parameter or a closure to define an object
     */
    public function offsetSet($id, $value)
    {
        $this->values[$id] = $value;
        $this->keys[$id] = true;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $id The unique identifier for the parameter or object
     * @return mixed The value of the parameter or an object
     * @throws InvalidArgumentException if the identifier is not defined
     */
    public function offsetGet($id)
    {
        if (!isset($this->keys[$id])) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (
            isset($this->raw[$id])
            || !is_object($this->values[$id])
            || !method_exists($this->values[$id], '__invoke')
        ) {
            return $this->values[$id];
        }

        $this->raw[$id] = $this->values[$id];
        return $this->values[$id] = $this->values[$id]($this);
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $id The unique identifier for the parameter or object
     * @return Boolean
     */
    public function offsetExists($id)
    {
        return array_key_exists($id, $this->values);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $id The unique identifier for the parameter or object
     */
    public function offsetUnset($id)
    {
        if (isset($this->keys[$id])) {
            unset($this->values[$id], $this->raw[$id], $this->keys[$id]);
        }
    }

    /**
     * Gets a parameter or the closure defining an object.
     *
     * @param string $id The unique identifier for the parameter or object
     * @return mixed The value of the parameter or the closure defining an object
     * @throws InvalidArgumentException if the identifier is not defined
     */
    public function raw($id)
    {
        if (!isset($this->keys[$id])) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (isset($this->raw[$id])) {
            return $this->raw[$id];
        }

        return $this->values[$id];
    }

    /**
     * Returns all defined value names.
     *
     * @return array An array of value names
     */
    public function keys()
    {
        return array_keys($this->values);
    }
}