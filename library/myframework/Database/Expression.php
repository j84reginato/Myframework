<?php

namespace Myframework\Database;

/**
 * Classe abstrata para permitir definição de expressões.
 *
 * @author Jonatan Noronha Reginato
 */
abstract class Expression
{
    const AND_OPERATOR = 'AND ';
    const OR_OPERATOR = 'OR ';

    abstract public function dump();
}
