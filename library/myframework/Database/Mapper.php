<?php

namespace Myframework\Database;

use Myframework\Entities\Entity;
use \Exception;

/**
 * Permite definir um Data Mapper.
 * Utiliza o Design Pattern Layer Supertype, ou seja, esta classe trata-se de
 * uma superclasse que reúne funcionalidades em comum para toda uma camada de
 * objetos.
 *
 * @author Jonatan Noronha Reginato
 */
abstract class Mapper extends Gateway
{
    // ------------------------------------------------------------------------
    /**
     * Método construtor que instancia um objeto Data Mapper.
     * Se passado o $id, já carrega o objeto do tipo entidade.
     * @param int $id O ID do objeto
     * @return Entity Um objeto Entity correspondente à classe instanciada
     */
    public function __construct($id = null)
    {
        if ($id) {
            $object = $this->load($id);
            return $object;
        }
    }

    // ------------------------------------------------------------------------
    /**
     * Recupera um objeto da base de dados pelo seu ID.
     * Este objeto será uma instância da classe Entity.
     * @param int $id O ID do objeto
     * @return Entity Um objeto Entity correspondente à classe instanciada
     * @throws Exception
     */
    public function load($id)
    {

        $sql = "SELECT * FROM " . self::TABLENAME . " WHERE id = :id";
        $params[] = array(':id', $id, 'int');
        $result = $this->query($sql, $params);
        if ($result) {
            $object = $this->fetchObject($this->getEntityClass());
        }
        return $object;
    }

    // ------------------------------------------------------------------------
    /**
     * Armazena o objeto (Entity) na base de dados.
     * @param Entity $entity Uma instancia da classe Entity
     * @return int O número de registros afetados pela consulta SQL
     * @throws Exception
     */
    public function store(Entity $entity)
    {
        $prepared = $this->prepare($entity);

        if (empty($entity->data['id']) || (!$this->load($entity->data['id']))) {
            if (empty($entity->data['id'])) {
                $entity->data['id'] = $this->getLast() + 1;
                $prepared['id'] = $entity->data['id'];
            }
            $sql =  "INSERT INTO {$this->getEntity()} " .
                    '(' . implode(', ', array_keys($prepared)) . ')' .
                    ' VALUES ' .
                    '(' . implode(', ', array_values($prepared)) . ')';

        } else {
            $sql = "UPDATE {$this->getEntity()}";
            if ($prepared) {
                foreach ($prepared as $column => $value) {
                    if ($column !== 'id') {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }
            $sql .= ' SET ' . implode(', ', $set);
            $sql .= ' WHERE id = ' . (int) $entity->data['id'];
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

    // ------------------------------------------------------------------------
    /**
     * Exclui o objeto (Entity) na base de dados.
     * @param Entity $entity Uma instancia da classe Entity
     * @return int O número de registros afetados pela consulta SQL
     * @throws Exception
     */
    public function delete(Entity $entity)
    {
        $id = (int) $entity->data['id'];
        $sql = "DELETE FROM {$this->getEntity()} WHERE id = {$id}";
        $conn = Transaction::get();
        if ($conn) {
            Transaction::log($sql);
            $result = $conn->exec($sql);
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!');
        }
    }

    //-------------------------------------------------------------------------
    /**
     * Método estático para busca um objeto pelo id.
     * Este objeto será uma instância da classe Entity.
     * @param int $id O ID do objeto
     * @return Entity Um objeto Entity correspondente à classe instanciada
     */
    public static function find($id)
    {
        $classname = get_called_class();
        $dataMapper = new $classname;
        return $dataMapper->load($id);
    }

    // ------------------------------------------------------------------------
    /**
     * Método estático que retorna todos objetos da base de dados.
     * Estes objetos estarão contidos num array de instâncias da classe Entity.
     * @return array Objetos Entity correspondente à classe instanciada
     */
    public static function all()
    {
        $classname = get_called_class();
        $repository = new Repository($classname);
        return $repository->load(new Criteria);
    }

    //-------------------------------------------------------------------------
    /**
     * Retorna o nome da entidade (tabela).
     * 
     * @return string A constante de classe ENTITY_TABLENAME
     */
    private function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::ENTITY_TABLENAME");
    }

    //-------------------------------------------------------------------------
    /**
     * Retorna o Namespace da classe da entidade a ser instanciada.
     * 
     * @return string A constante de classe ENTITY_NAMESPACE
     */
    private function getEntityClass()
    {
        $class = get_class($this);
        return constant("{$class}::ENTITY_NAMESPACE");
    }

    // ------------------------------------------------------------------------
    /**
     * Método responsável pelo tratamento dos dados dos atributos do objeto.
     * @param Entity $entity Uma instancia da classe Entity
     * @return array
     */
    public function prepare(Entity $entity)
    {
        $prepared = array();
        foreach ($entity->data as $key => $value) {
            if (is_scalar($value)) {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    // ------------------------------------------------------------------------
    /**
     * Realiza o "escape" os dados recebidos
     * @param mixed $value Dados escalares
     * @return string Dados após "escape"
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

    // ------------------------------------------------------------------------
    /**
     * Retorna o último ID da Entidade
     * @return mixed O último registro da tabela do banco de dados
     * @throws Exception
     */
    private function getLast()
    {
        $conn = Transaction::get();
        if ($conn) {
            $sql = "SELECT MAX(id) FROM {$this->getEntity()}";
            Transaction::log($sql);
            $result = $conn->query($sql);
            $row = $result->fetch();
            return $row[0];
        } else {
            throw new Exception('Não há transação ativa!!');
        }
    }
}
