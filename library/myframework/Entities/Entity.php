<?php

namespace Myframework\Entities;

/**
 * Permite definir um Entity - objeto transportador de dados.
 * Utiliza o Design Pattern Layer Supertype, ou seja, esta classe trata-se de
 * uma superclasse que reúne funcionalidades em comum para toda uma camada de
 * objetos.
 *
 * @author Jonatan Noronha Reginato
 */
abstract class Entity implements EntityInterface
{
    protected $data;

    // ------------------------------------------------------------------------
    /**
     * Define os valores dos atributos do objeto instanciado.
     *
     * @param string $property O atributo do objeto
     * @param mixed $value O valor do atributo do objeto
     */
    public function __set($property, $value)
    {
        if (method_exists($this, 'set' . ucfirst($property))) {
            call_user_func(array($this, 'set' . ucfirst($property)), $value);
        } else {
            if ($value === null) {
                unset($this->data[$property]);
            } else {
                $this->data[$property] = $value;
            }
        }
    }

    // ------------------------------------------------------------------------
    /**
     * Retorna o valor do atributo do objeto passado como parâmetro.
     *
     * @param string $property O atributo do objeto
     * @return mixed O valor do atributo do objeto passado como parâmetro
     */
    public function __get($property)
    {
        if (method_exists($this, 'get' . ucfirst($property))) {
            return call_user_func(array($this, 'get' . ucfirst($property)));
        } else {
            if (isset($this->data[$property])) {
                return $this->data[$property];
            }
        }
    }

    // ------------------------------------------------------------------------
    /**
     * Este método será executado automaticamente sempre que se for testar a
     * presença de um valor no objeto, como ao utilizar a função isset()
     *
     * @param string $property O atributo do objeto
     * @return boolean
     */
    public function __isset($property)
    {
        return isset($this->data[$property]);
    }

    // ------------------------------------------------------------------------
   /**
     * Este método será executado sempre que um objeto for clonado.
     * O novo objeto manterá todas as propriedades do objeto original, com
     * exceção de seu ID.
     */
    public function __clone()
    {
        unset($this->data['id']);
    }
}
