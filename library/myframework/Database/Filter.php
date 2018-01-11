<?php

namespace Myframework\Database;

/**
 * Permite definir Filtros à uma Expressão para a seleção de registros no BD.
 *
 * @author Jonatan Noronha Reginato
 */
class Filter extends Expression
{
    private $variable;
    private $operator;
    private $value;

    // ------------------------------------------------------------------------
    /**
     * Instancia um novo Filtro.
     * 
     * Exemplos:
     * - id > 10.
     * - usuário = Jonatan Noronha Reginato.
     *
     * @param $variable Variável
     * @param $operator Operador (>,<)
     * @param $value Valor a ser comparado
     */
    public function __construct($variable, $operator, $value)
    {
        // Armazena as propriedades
        $this->variable = $variable;
        $this->operator = $operator;

        // Transforma o valor de acordo com certas regras antes de atribuir à
        // propriedade $this->value
        $this->value = $this->transform($value);
    }

    // ------------------------------------------------------------------------
    /**
     * Recebe um valor e faz as modificações necessárias para ele ser
     * interpretado pelo banco de dados.
     *
     * @param mixed $value Valor a ser transformado
     * @return mixed Valor transformado
     */
    private function transform($value)
    {
        // Caso seja um array
        if (is_array($value)) {
            foreach ($value as $x) {
                if (is_integer($x)) {
                    $foo[] = $x;
                } else if (is_string($x)) {
                    $foo[] = "'$x'";
                }
            }
            $result = '(' . implode(',', $foo) . ')';
        }
        // Caso seja uma string
        elseif (is_string($value)) {
            $result = "'$value'";
        }
        // Caso seja valor nulo
        elseif (is_null($value)) {
            $result = 'NULL';
        }
        // Caso seja booleano
        elseif (is_bool($value)) {
            $result = $value ? 'TRUE' : 'FALSE';
        // Caso seja outro tipo quaquer
        } else {
            $result = $value;
        }

        // Retorna o valor
        return $result;
    }

    // ------------------------------------------------------------------------
    /**
     * Retorna o filtro em forma de expressão.
     */
    public function dump()
    {
        // Concatena a expressão
        return "{$this->variable} {$this->operator} {$this->value}";
    }
}