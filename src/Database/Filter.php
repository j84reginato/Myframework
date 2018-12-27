<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

/**
 * Permite definir Filtros à uma Expressão para a seleção de registros no BD.
 */
class Filter extends Expression
{
    /**
     * @var string
     */
    private $variable;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $value;

    /**
     * Instancia um novo Filtro.
     *
     * Exemplos:
     * - id > 10.
     * - usuário = Jonatan Noronha Reginato.
     *
     * @param string $variable Variável
     * @param string $operator Operador (>,<)
     * @param string $value Valor a ser comparado
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
                } elseif (is_string($x)) {
                    $foo[] = "'$x'";
                }
            }
            return '(' . implode(',', $foo) . ')';
        }

        // Caso seja uma string
        if (is_string($value)) {
            return "'$value'";
        }

        // Caso seja valor nulo
        if (is_null($value)) {
            return 'NULL';
        }

        // Caso seja booleano
        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        // Caso seja outro tipo quaquer
        return $value;
    }

    /**
     * Retorna o filtro em forma de expressão.
     */
    public function dump()
    {
        // Concatena a expressão
        return "{$this->variable} {$this->operator} {$this->value}";
    }

}