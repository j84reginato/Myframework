<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

/**
 * Classe abstrata para permitir definição de expressões.
 */
abstract class Expression
{
    /**
     * Constantes da classe.
     */
    const AND_OPERATOR = 'AND ';
    const OR_OPERATOR = 'OR ';

    /**
     * dump.
     */
    abstract public function dump();
}
