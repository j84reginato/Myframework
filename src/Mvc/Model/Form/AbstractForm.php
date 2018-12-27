<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Model/Form
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Model\Form;

use j84Reginato\MyFramework\StdClass\ParametersInterface;

/**
 * Utiliza o Design Pattern Layer Supertype, ou seja, esta classe trata-se de
 * uma superclasse que reúne funcionalidades em comum para toda uma camada de
 * objetos.
 */
abstract class AbstractForm implements FormInterface
{
    /**
     * Método construtor.
     *
     * @param ParametersInterface $oPost
     * @return boolean
     */
    public function __construct(ParametersInterface $oPost)
    {
        foreach ($oPost as $key => $value) {
            $this->{$key} = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }

}
