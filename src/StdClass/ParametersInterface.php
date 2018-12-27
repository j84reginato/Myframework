<?php

/**
 * @package MyFramework
 * @category StdClass
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\StdClass;

use ArrayAccess;
use Countable;
use Serializable;
use Traversable;

/**
 * ParametersInterface.
 */
interface ParametersInterface extends ArrayAccess, Countable, Serializable, Traversable
{
    /**
     * Método construtor.
     *
     * @param array $values
     */
    public function __construct(array $values = null);

    /**
     * fromArray.
     *
     * @param array $values
     * @return mixed
     */
    public function fromArray(array $values);

    /**
     * fromString.
     *
     * @param $string
     * @return mixed
     */
    public function fromString($string);

    /**
     * toArray.
     *
     * @return mixed
     */
    public function toArray();

    /**
     * toString.
     *
     * @return mixed
     */
    public function toString();

    /**
     * Get
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set
     *
     * @param string $name
     * @param mixed $value
     * @return ParametersInterface
     */
    public function set($name, $value);

}
