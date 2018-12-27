<?php

/**
 * @package MyFramework
 * @category StdClass
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\StdClass;

use ArrayObject as PhpArrayObject;

/**
 * Parameters.
 */
class Parameters extends PhpArrayObject implements ParametersInterface
{
    /**
     * Método construtor.
     *
     * @param array $values
     */
    public function __construct(array $values = null)
    {
        if (null === $values) {
            $values = [];
        }
        parent::__construct($values, PhpArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * fromArray.
     *
     * @param array $values
     * @return void
     */
    public function fromArray(array $values)
    {
        $this->exchangeArray($values);
    }

    /**
     * fromString.
     *
     * @param string $string
     * @return void
     */
    public function fromString($string)
    {
        $array = [];
        parse_str($string, $array);
        $this->fromArray($array);
    }

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * toString.
     *
     * @return string
     */
    public function toString()
    {
        return http_build_query($this->toArray());
    }

    /**
     * offsetGet.
     *
     * @param string $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        if ($this->offsetExists($name)) {
            return parent::offsetGet($name);
        }
        return;
    }

    /**
     * get.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ($this->offsetExists($name)) {
            return parent::offsetGet($name);
        }
        return $default;
    }

    /**
     * set.
     *
     * @param string $name
     * @param mixed $value
     * @return Parameters
     */
    public function set($name, $value)
    {
        $this[$name] = $value;
        return $this;
    }

}
