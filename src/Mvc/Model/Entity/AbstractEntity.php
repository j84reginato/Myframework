<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Model/Entity
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Model\Entity;

/**
 * Permite definir uma Entity.
 *
 * Utiliza o Design Pattern Layer Supertype, ou seja, trata-se de uma super
 * classe que reúne funcionalidades em comum para toda uma camada de objetos.
 */
class AbstractEntity extends AbstractActiveRecord implements EntityInterface
{
    /**
     * Um array contendo os atributos da Entity.
     *
     * @var array
     */
    protected $aData = [];

    /**
     * {@inheritDoc}
     */
    public function __set($sProperty, $mValue)
    {
        if (method_exists($this, 'set' . ucfirst($sProperty))) {
            call_user_func([$this, 'set' . ucfirst($sProperty)], $mValue);
            return false;
        }

        if ($mValue === null) {
            unset($this->aData[$sProperty]);
            return false;
        }

        $this->aData[$sProperty] = $mValue;
    }

    /**
     * {@inheritDoc}
     */
    public function __get($sProperty)
    {
        if (isset($this->aData[$sProperty])) {
            return $this->aData[$sProperty];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function __isset($sProperty)
    {
        return isset($this->aData[$sProperty]);
    }

    /**
     * {@inheritDoc}
     *
     * O novo objeto manterá todas as propriedades do objeto original, com
     * exceção de seu ID.
     */
    public function __clone()
    {
        unset($this->aData[$this->getPrimaryKey()]);
    }

    /**
     * {@inheritDoc}
     */
    public function fromArray($aData)
    {
        $this->aData = $aData;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->aData;
    }

    /**
     * Método utilizado para preencher os atributos de um Active Record com os
     * valores dos atributos de um outro objeto.
     *
     * @param object $oObject
     */
    public function fromObject($oObject)
    {
        foreach ($oObject as $property => $value) {
            $this->{$property} = $value;
        }
    }

}