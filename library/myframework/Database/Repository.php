<?php

namespace Myframework\Database;

use \Exception;

/**
 * Classe reponsável por manipular coleções de objetos.
 * Utiliza o Design Patter Repository.
 * Um Repository, ou repositório, é uma camada na aplicação que trata de mediar
 * a comunicação entre os objetos de negócio e o banco de dados, atuando como
 * um gerenciador de coleções de objetos. Uma classe Repository deve aceitar
 * critérios que permitam selecionar um determinado grupo de objetos de forma
 * flexível. Os objetos devem ser selecionados, excluídos e retornados a partir
 * de uma classe Repository por meio da especificação de critérios.
 *
 * @author Jonatan Noronha Reginato
 */
final class Repository
{
    private $dataMapper; // classe manipulada pelo repositório

    // ------------------------------------------------------------------------
    /**
     * Instancia um Repositório de objetos
     *
     * @param $class = Classe dos Objetos
     */
    function __construct($class)
    {
        $this->dataMapper = $class;
    }

    // ------------------------------------------------------------------------
    /**
     * Carrega um conjunto de objetos (collection) da base de dados
     *
     * @param $criteria = objeto do tipo Criteria
     */
    function load(Criteria $criteria)
    {
        // Instancia a instrução de SELECT
        $sql = "SELECT * FROM " . constant($this->dataMapper . '::TABLENAME');

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
                $sql .= ' ORDER BY ' . $order;
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
            $results = array();

            if ($result) {
                // Percorre os resultados da consulta, retornando um objeto
                while ($row = $result->fetchObject($this->dataMapper)) {
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

    // ------------------------------------------------------------------------
    /**
     * Excluir um conjunto de objetos (collection) da base de dados
     *
     * @param $criteria = objeto do tipo Criteria
     */
    function delete(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "DELETE FROM " . constant($this->dataMapper . '::TABLENAME');
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

    // ------------------------------------------------------------------------
    /**
     * Retorna a quantidade de objetos da base de dados que satisfazem um
     * determinado critério de seleção.
     * @param $criteria = objeto do tipo TCriteria
     */
    function count(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "SELECT count(*) FROM " . constant($this->dataMapper . '::TABLENAME');
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
