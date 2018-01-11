<?php

namespace Myframework\Database;

/**
 * Permite definir Critérios à uma Expressão para a seleção de registros no BD.
 *
 * @author Jonatan Noronha Reginato
 */
class Criteria extends Expression
{
    private $expressions;
    private $operators;
    private $properties;

    // ------------------------------------------------------------------------
    /**
     * Instancia um novo Critério.
     * 
     * Exemplos:
     * 
     * composto por filtros:
     * - (id > 10) AND (usuário = Jonatan Noronha Reginato).
     * 
     * composto por outros critérios:
     * - (id > 10 AND usuário = Jonatan) OR (id = 1 AND usuario = Teste).
     */
    public function __construct()
    {
        $this->expressions = array();
        $this->operators = array();
    }

    // ------------------------------------------------------------------------
    /**
     * Adiciona uma expressão ao critério.
     *
     * @param Expression $expression Uma instancia da classe Expression
     * @param integer $operator Operador lógico de concatenação
     */
    public function add(Expression $expression, $operator = self::AND_OPERATOR)
    {
        // Na primeira vez, não precisamos de operador lógico para concatenar
        if (empty($this->expressions)) {
            $operator = NULL;
        }

        // Agrega o resultado da expressão à lista de expressões
        $this->expressions[] = $expression;
        $this->operators[] = $operator;
    }

    // ------------------------------------------------------------------------
    /**
     * Concatena a lista de expressões e retorna a expressão final.
     *
     * @return string A expressão final
     */
    public function dump()
    {
        $result = '';
        if (is_array($this->expressions)) {
            if (count($this->expressions) > 0) {
                foreach ($this->expressions as $i => $expression) {
                    $operator = $this->operators[$i];
                    // Concatena o operador com a respectiva expressão
                    $result .= $operator . $expression->dump() . ' ';
                }
                $result = trim($result);
                return "({$result})";
            }
        }
    }

    // ------------------------------------------------------------------------
    /**
     * Define o valor de uma propriedade
     *
     * @param $property Nome da propriedade (ORDER BY, OFFSET, LIMIT)
     * @param $value Valor da propriedade
     */
    public function setProperty($property, $value)
    {
        if (isset($value)) {
            $this->properties[$property] = $value;
        } else {
            $this->properties[$property] = null;
        }
    }

    // ------------------------------------------------------------------------
    /**
     * Retorna o valor de uma propriedade
     *
     * @param $property Nome da propriedade
     */
    public function getProperty($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
    }
}
