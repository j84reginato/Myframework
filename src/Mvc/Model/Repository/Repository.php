<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Model/Repository
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Model\Repository;

use Exception;
use j84Reginato\MyFramework\Database\Columns;
use j84Reginato\MyFramework\Database\Criteria;
use j84Reginato\MyFramework\Database\Join;
use j84Reginato\MyFramework\Database\Transaction;
use j84Reginato\MyFramework\Log\LoggerTXT;

/**
 * Classe reponsável por manipular coleções de objetos.
 * Utiliza o Design Patter Repository.
 * Um Repository, ou repositório, é uma camada na aplicação que trata de mediar
 * a comunicação entre os objetos de negócio e o banco de dados, atuando como
 * um gerenciador de coleções de objetos. Uma classe Repository deve aceitar
 * critérios que permitam selecionar um determinado grupo de objetos de forma
 * flexível. Os objetos devem ser selecionados, excluídos e retornados a partir
 * de uma classe Repository por meio da especificação de critérios.
 */
class Repository
{
    /**
     * Classe manipulada pelo repositório.
     *
     * @var string
     */
    protected $activeRecord;

    /**
     * Instancia um Repositório de objetos.
     *
     * @param string $class
     */
    function __construct($class)
    {
        $this->activeRecord = $class;
    }

    /**
     * Método que retorna todos objetos da base de dados.
     *
     * @return array
     */
    public function getAll()
    {
        try {
            Transaction::open();
            Transaction::setLogger(new LoggerTXT('log_usuario_repository.txt'));

            $oColumns = new Columns(
                constant($this->activeRecord . '::DB_SCHEMA'),
                constant($this->activeRecord . '::TABLE_NAME'),
                ['*']
            );
            $oJoin = new Join();
            $oCriteria = new Criteria();

            return $this->load($oColumns, $oJoin, $oCriteria);

        } catch (Exception $ex) {
            header("HTTP/1.0 400 Bad Request");
            return [
                'status' => 'danger',
                'message' => $ex->getMessage()
            ];
        }
    }

    /**
     * Carrega um conjunto de objetos (collection) da base de dados.
     *
     * @param Join $joins
     * @param Criteria $criteria
     */
    function load(Columns $columns, Join $joins, Criteria $criteria)
    {
        // Instancia a instrução de SELECT
        $sql = 'SELECT ';
        $sql .= $columns->getColumns();

        $joinSpcParams = '';

        // Obtém a cláusula JOIN.
        if ($joins) {
            foreach ($joins->getJoins() as $join) {
                foreach ($join['aColumns'] as $sColumn) {
                    $sql .= ", {$sColumn}";
                }
                $joinSpcParams .= " {$join['sJoinType']}  {$join['sTable']} ON {$join['sOn']}";
            }
        }

        $sql .= ' FROM '
               . constant($this->activeRecord . '::DB_SCHEMA') . '.'
               . constant($this->activeRecord . '::TABLE_NAME');

        $sql .= "{$joinSpcParams}";

        // Obtém a cláusula WHERE do objeto criteria.
        if ($criteria) {
            $expression = $criteria->dump();
            if ($expression) {
                $sql .= ' WHERE ' . $expression;
            }

            // Obtém as propriedades do critério
            $order = $criteria->getProperty('order');
            $limit = $criteria->getProperty('limit');
            $offset = $criteria->getProperty('offset');

            // Obtém a ordenação do SELECT
            if ($order) {
                $sql .= ' ORDER BY ' . $order . ' DESC';
            }
            if ($limit) {
                $sql .= ' LIMIT ' . $limit;
            }
            if ($offset) {
                $sql .= ' OFFSET ' . $offset;
            }
        }

        // Obtém transação ativa
        $conn = Transaction::get();
        if ($conn) {
            // Registra mensagem de log
            Transaction::log($sql);

            // Executa a consulta no banco de dados
            $result = $conn->query($sql);
            $results = [];

            if ($result) {
                // Percorre os resultados da consulta, retornando um objeto
                while ($row = $result->fetchObject($this->activeRecord)) {
                    // Armazena no array $results;
                    $results[] = $row;
                }
            }
            return $results;
        } else {
            // Se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Excluir um conjunto de objetos (collection) da base de dados
     *
     * @param $criteria = objeto do tipo Criteria
     */
    function delete(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME');
        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }

        // Obtém transação ativa
        $conn = Transaction::get();
        if ($conn) {
            // Registra mensagem de log
            Transaction::log($sql);
            // Executa instrução de DELETE
            $result = $conn->exec($sql);
            return $result;
        } else {
            // Se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Retorna a quantidade de objetos da base de dados que satisfazem um
     * determinado critério de seleção.
     *
     * @param Criteria $criteria
     */
    function count(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "SELECT count(*) FROM " . constant($this->activeRecord . '::TABLENAME');
        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }

        // Obtém transação ativa
        $conn = Transaction::get();
        if ($conn) {
            // Registra mensagem de log
            Transaction::log($sql);

            // Executa instrução de SELECT
            $result = $conn->query($sql);
            if ($result) {
                $row = $result->fetch();
            }
            // Retorna o resultado
            return $row[0];
        } else {
            // Se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

}
