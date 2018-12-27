<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

/**
 * Classe responsável pela configuração da camada Model.
 */
class Model extends Gateway
{
    protected function select($sql, $params = null, $direct = false)
    {
        if ($params == null && $direct == true) {
            $this->directQuery($sql);
        } else {
            $this->query($sql, $params);
        }
        $result = array();
        while ($row = $this->fetchObject()) {
            $result[] = $row;
        }
        return $result;
    }

    protected function select1($sql)
    {
        try {
            $conn = Transaction::get();
            $state = $conn->prepare($sql);
            $state->execute();
        } catch (\PDOException $ex) {
            die($ex->getMessage() . ' ' . $sql);
        }
        $result = array();
        while ($row = $this->fetchObject()) {
            $result[] = $row;
        }
        return $result;
    }

    protected function insert($obj, $table)
    {
        $sql = "INSERT INTO {$table} (".implode(',', array_keys((array) $obj)).") VALUES ('".implode("','", array_values((array) $obj))."')";
        $conn = Transaction::get();
        $state = $conn->query($sql);
        if ($state) {
            return array(
                'success' => true,
                'feedback' => 'Registro gravado com sucesso',
                'code' => $this->last($table)
            );
        }
    }

    protected function update($obj, $condition, $table)
    {
        foreach ($obj as $key => $value) {
            $data[] = "`{$key}` = " . (is_null($value) ? "NULL" : "'{$value}'");
        }
        foreach ($condition as $key => $value) {
            $where[] = "`{$key}` " . (is_null($value) ? "IS NULL" : "= '{$value}'");
        }
        $sql = "UPDATE {$table} SET " . implode(', ', $data) . " WHERE " . implode(' AND ', $where);
        $conn = Transaction::get();
        $state = $conn->query($sql);
        if ($state) {
            return array(
                'success' => true,
                'feedback' => 'Registro atualizado com sucesso'
            );
        }
    }

    protected function delete($condition, $table)
    {
        foreach ($condition as $key => $value) {
            $where[] = "`{$key}`" . (is_null($value) ? " IS NULL " : " = '{$value}'");
        }
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $where);
        $conn = Transaction::get();
        $state = $conn->query($sql);
        if ($state) {
            return array(
                'success' => true,
                'feedback' => 'Registro excluído com sucesso'
            );
        }
    }

    protected function last()
    {
        //$result = $this->conn->fetchObject("SELECT last_insert_id() AS last FROM {$table}");
        //return $result->last;
        $conn = Transaction::get();
        return $conn->lastInsertId();
    }

    protected function first($obj)
    {
        if (isset($obj[0])) {
            return $obj[0];
        } else {
            return null;
        }
    }

    protected function setObject($obj, $values, $exists = true)
    {
        if(is_object($obj)) {
            if (count($values) > 0) {
                foreach ($values as $key => $value) {
                    if (property_exists($obj, $key) || $exists) {
                        $obj->$key = $value;
                    }
                }
            } else {
                $obj_vars = get_object_vars($obj);
                foreach ($obj_vars as $key => $value) {
                    $obj->$key = null;
                }
            }
        }
    }
}
