<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

use Countable;
use Exception;
use Iterator;

/**
 * Agrega as especificações de JOIN.
 *
 * Cada especificação é uma matriz com as seguintes chaves:
 *
 * - sTable: o nome da tabela na qual o JOIN ocorre
 * - sOn: a condição de igualdade
 * - aColumns: as colunas a incluir com a operação JOIN
 * - sJoinType: o tipo de JOIN a ser executado; veja as constantes `JOIN_*`
 */
class Join implements Iterator, Countable
{
    const JOIN_INNER = 'INNER JOIN';
    const JOIN_OUTER = 'OUTER JOIN';
    const JOIN_LEFT = 'LEFT JOIN';
    const JOIN_RIGHT = 'RIGHT JOIN';
    const JOIN_LEFT_OUTER  = 'LEFT OUTER JOIN';
    const JOIN_RIGHT_OUTER = 'RIGHT OUTER JOIN';

    /**
     * Posição atual do iterador.
     *
     * @var integer
     */
    private $iPosition = 0;

    /**
     * cláusulas JOIN.
     *
     * @var array
     */
    protected $aJoins = [];

    /**
     * Inicializa a posição do iterador.
     */
    public function __construct()
    {
        $this->iPosition = 0;
    }

    /**
     * Rebobina o iterador.
     */
    public function rewind()
    {
        $this->iPosition = 0;
    }

    /**
     * Retornar a cláusula JOIN atual.
     *
     * @return array
     */
    public function current()
    {
        return $this->aJoins[$this->iPosition];
    }

    /**
     * Retorna o índice do iterador atual.
     *
     * @return integer
     */
    public function key()
    {
        return $this->iPosition;
    }

    /**
     * Avanca para a próxima cláusula JOIN.
     */
    public function next()
    {
        ++$this->iPosition;
    }

    /**
     * O iterador está em uma posição válida?
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->aJoins[$this->iPosition]);
    }

    /**
     * Retorna as cláusulas JOIN.
     *
     * @return array
     */
    public function getJoins()
    {
        return $this->aJoins;
    }

    /**
     * @param string $sTable
     * @param string $sOn
     * @param string|array $aColumns
     * @param string $sJoinType
     * @return self
     * @throws Exception
     */
    public function join($sTable, $sOn, $aColumns = ['*'], $sJoinType = self::JOIN_INNER)
    {
        if (! isset($sTable) || (! is_string($sTable))) {
            throw new Exception("Erro na cláusula JOIN da consulta");
        }

        if (! is_array($aColumns)) {
            $aColumns = [$aColumns];
        }

        $this->aJoins[] = [
            'sTable' => $sTable,
            'sOn' => $sOn,
            'aColumns' => $aColumns,
            'sJoinType' => $sJoinType ? $sJoinType : self::JOIN_INNER
        ];

        return $this;
    }

    /**
     * Redefinir para uma lista vazia de cláusulas JOIN.
     *
     * @return self
     */
    public function reset()
    {
        $this->aJoins = [];
        return $this;
    }

    /**
     * Obtém contagem de cláusulas anexadas.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->aJoins);
    }

}
