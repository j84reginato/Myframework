<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Model/Entity
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Model\Entity;

use Exception;
use j84Reginato\MyFramework\Database\Criteria;
use j84Reginato\MyFramework\Database\Transaction;
use j84Reginato\MyFramework\Mvc\Model\Repository\Repository;

/**
 * Utiliza o Design Pattern Layer Supertype, ou seja, esta classe trata-se de
 * uma superclasse que reúne funcionalidades em comum para toda uma camada de
 * objetos.
 */
abstract class AbstractActiveRecord implements ActiveRecordInterface
{
    /**
     * Método construtor.
     * Se passado o $id, já carrega o objeto do tipo entidade.
     *
     * @param integer $id
     * @return boolean
     */
    public function __construct($id = null)
    {
        if (isset($id) && ! empty($id)) {
            $object = $this->load($id);
            return $object;
        }
    }

    /**
     *
     * @return type
     */
    protected function getSchema()
    {
        $class = get_class($this);
        return constant("{$class}::DB_SCHEMA");
    }

    /**
     *
     * @return type
     */
    protected function getTablename()
    {
        $class = get_class($this);
        return constant("{$class}::TABLE_NAME");
    }

    /**
     *
     * @return type
     */
    protected function getPrimaryKey()
    {
        $class = get_class($this);
        return constant("{$class}::PRIMARY_KEY");
    }

    /**
     * Recupera um objeto da base de dados pelo seu ID.
     * Este objeto será uma instância da classe Entity.
     *
     * @param type $id
     * @return type
     */
    public function load($id)
    {
        $iId = (int) $id;

        $sql =
            "SELECT * " .
            "FROM {$this->getSchema()}.{$this->getTablename()} " .
            "WHERE {$this->getPrimaryKey()} = {$iId}";

        $conn = Transaction::get();
        if ($conn) {
            Transaction::log($sql);
            // $params[] = array(':id', $id, 'int');
            // $result = $this->query($sql, $params);
            $result = $conn->query($sql);
        }
        if ($result) {
            $object = $result->fetchObject(get_class($this));
        }
        return $object;
    }

    /**
     * Armazena o objeto (Entity) na base de dados.
     *
     * @return type
     * @throws Exception
     */
    public function store()
    {
        $prepared = $this->prepare($this);

        if (empty($this->getPrimaryKey()) || ! $this->load($this->{$this->getPrimaryKey()})) {
            $sql =  "INSERT INTO {$this->getSchema()}.{$this->getTablename()} " .
                    '(' . implode(', ', array_keys($prepared)) . ')' .
                    ' VALUES ' .
                    '(' . implode(', ', array_values($prepared)) . ')';

        } else {
            $sql = "UPDATE {$this->getSchema()}.{$this->getTablename()}";
            if ($prepared) {
                foreach ($prepared as $column => $value) {
                    if ($column !== $this->getPrimaryKey()) {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }
            $sql .= ' SET ' . implode(', ', $set);
            $sql .= ' WHERE ' . $this->getPrimaryKey() . ' = ' . (int) $this->{$this->getPrimaryKey()};
        }

        $conn = Transaction::get();
        if ($conn) {
            Transaction::log($sql);
            $result = $conn->exec($sql);
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Exclui o objeto (Entity) na base de dados.
     *
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function delete($id = null)
    {
        $iId = $id ? $id : $this->{$this->getPrimaryKey()};

        $sql = "DELETE FROM {$this->getSchema()}.{$this->getTablename()} WHERE {$this->getPrimaryKey()} = {$iId}";
        $conn = Transaction::get();
        if ($conn) {
            Transaction::log($sql);
            $result = $conn->exec($sql);
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Método estático para busca um objeto pelo id.
     * Este objeto será uma instância da classe Entity.
     *
     * @param type $id
     * @return type
     */
    public static function find($id)
    {
        $classname = get_called_class();
        $entity = new $classname;
        return $entity->load($id);
    }

    /**
     * Método estático que retorna todos objetos da base de dados.
     * Estes objetos estarão contidos num array de instâncias da classe Entity.
     *
     * @return type
     */
    public static function all()
    {
        $classname = get_called_class();
        $repository = new Repository($classname);
        return $repository->load(new Criteria());
    }

    /**
     * Método responsável pelo tratamento dos dados dos atributos do objeto.
     *
     * @param type $entity
     * @return type
     */
    public function prepare($entity)
    {
        $prepared = [];
        foreach ($entity->aData as $key => $value) {
            if (is_scalar($value)) {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    /**
     * Realiza o "escape" os dados recebidos
     *
     * @param type $value
     * @return string
     */
    public function escape($value)
    {
        // Verifica se é um dado escalar (string, inteiro, ...)
        if (is_scalar($value)) {
            if (is_string($value) and ( !empty($value))) {
                // Adiciona \ em aspas
                $value = addslashes($value);
                // Caso seja uma string
                return "'$value'";
            } else if (is_bool($value)) {
                // Caso seja um boolean
                return $value ? 'TRUE' : 'FALSE';
            } else if ($value !== '') {
                // Caso seja outro tipo de dado
                return $value;
            } else {
                // Caso seja NULL
                return "NULL";
            }
        }
    }

    /**
     * Retorna o último ID da Entidade
     *
     * @return type
     * @throws Exception
     */
    private function getLast()
    {
        $conn = Transaction::get();
        if ($conn) {
            $sql = "SELECT MAX({$this->getPrimaryKey()}) FROM {$this->getTablename()}";
            Transaction::log($sql);
            $result = $conn->query($sql);
            $row = $result->fetch();
            return $row[0];
        } else {
            throw new Exception('Não há transação ativa!!');
        }
    }

}
