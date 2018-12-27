<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

/**
 * Permite definir Critérios à uma Expressão para a seleção de registros no BD.
 */
class Criteria extends Expression
{
    /**
     * @var array
     */
    private $expressions;

    /**
     * @var array
     */
    private $operators;

    /**
     * @var string
     */
    private $properties;

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
        $this->expressions = [];
        $this->operators = [];
    }

    /**
     * Adiciona uma expressão ao critério.
     *
     * @param Expression $expression Uma instancia da classe Expression
     * @param string $operator Operador lógico de concatenação
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

    /**
     * Define o valor de uma propriedade.
     *
     * @param string $property Nome da propriedade (ORDER BY, OFFSET, LIMIT)
     * @param string $value Valor da propriedade
     */
    public function setProperty($property, $value)
    {
        if (isset($value)) {
            $this->properties[$property] = $value;
        } else {
            $this->properties[$property] = null;
        }
    }

    /**
     * Retorna o valor de uma propriedade.
     *
     * @param string $property
     * @return string
     */
    public function getProperty($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
    }

}
