<?php

namespace Myframework\Database;

/**
 * Classe com métodos estáticos que realizam uma conexão com o Banco de Dados
 * utilizando a biblioteca PDO.
 *
 * @author Jonatan Noronha Reginato
 */
final class Connection extends ConnectionConfig
{
    // ------------------------------------------------------------------------
    /**
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que uma instância desta classe seja
     * criada externamente à classe através do operador "new".
     */
    private function __construct() {}

    // ------------------------------------------------------------------------
    /**
     * Este método realiza a conexão ao banco de dados com uso do driver PDO.
     * Usa o Design Pattern Factory para definir a escolha do tipo de BD.
     */
    public static function open()
    {
        switch (self::DB_DATATYPE) {
            case 'mysql':
                $conStr = 'mysql:' .
                          'host=' . self::DB_HOSTNAME . ';' .
                          'port=' . self::DB_DATAPORT . ';' .
                          'dbname=' . self::DB_DATABASE . ';' .
                          'charset=' . self::DB_CHARSET;
                $conn = new \PDO($conStr, self::DB_USERNAME, self::DB_PASSWORD);
                break;
            case 'pgsql':
                $conStr = 'pgsql:' .
                          'dbname=' . self::DB_DATABASE . ';' .
                          'user=' . self::DB_USERNAME . ';' .
                          'password=' . self::DB_PASSWORD . ';' .
                          'host=' . self::DB_HOSTNAME . ';' .
                          'port=' . self::DB_DATAPORT . ';' .
                          'charset=' . self::DB_CHARSET;
                $conn = new \PDO($conStr);
                break;
        }
        try {
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $conn;
        } catch (\PDOException $e) {
            self::errorHandler($e->getMessage());
            exit();
        }
    }

    // ------------------------------------------------------------------------
    /**
     * Método para tratamento de erro em caso de falha na conexão com o bd.
     *
     * @param string $error Mensagem de erro
     */
    public static function errorHandler($error)
    {
        if (!self::ERROR_SUPRESS) {
            print_r($error);
        }
    }
}
