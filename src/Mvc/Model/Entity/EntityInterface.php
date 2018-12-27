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
 * Interface que descreve os métodos necessários para definir uma Entity.
 */
interface EntityInterface
{
    /**
     * Define os valores dos atributos do objeto instanciado.
     *
     * @param string $sProperty O nome do atributo
     * @param string|integer|boolean|float|null $mValue O valor do atributo
     */
    public function __set($sProperty, $mValue);

    /**
     * Retorna o valor do atributo requisitado.
     *
     * @param string $sProperty O nome do atributo
     * @return string|integer|boolean|float|null
     */
    public function __get($sProperty);

    /**
     * Este método será executado automaticamente sempre que se for testar a
     * presença de um valor no objeto, como ao utilizar a função isset().
     *
     * @param string $sProperty O nome do atributo
     * @return boolean
     */
    public function __isset($sProperty);

    /**
     * Este método será executado sempre que um objeto for clonado.
     */
    public function __clone();

    /**
     * Método utilizado para preencher os atributos de um Active Record com os
     * dados de um array, de modo que os índices desse array são os atributos
     * do objeto.
     *
     * @param array $aData
     */
    public function fromArray($aData);

    /**
     * Método utilizado para retornar todosos atributos de um objeto
     * (armazenados na propriedade $aData) em forma de array.
     */
    public function toArray();

}