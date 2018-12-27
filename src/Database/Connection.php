<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Classe com métodos estáticos que realizam uma conexão com o Banco de Dados
 * utilizando a biblioteca PDO.
 */
class Connection
{
    /**
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que uma instância desta classe seja
     * criada externamente à classe através do operador "new".
     */
    private function __construct()
    {

    }

    /**
     * Este método realiza a conexão ao banco de dados com uso do driver PDO.
     */
    public static function open()
    {
        $aGlobalConfigs = require CONFIG_PATH . 'autoload/global.php';
        if (! isset($aGlobalConfigs['db']) || ! count($aGlobalConfigs['db'])) {
            return false;
        }

        self::checkPdoExtension();

        switch ($aGlobalConfigs['db']['datatype']) {
            case 'mysql':
                $conStr = 'mysql:' .
                          'host=' . $aGlobalConfigs['db']['hostname'] . ';' .
                          'port=' . $aGlobalConfigs['db']['connport'] . ';' .
                          'dbname=' . $aGlobalConfigs['db']['database'] . ';' .
                          'charset=' . $aGlobalConfigs['db']['charset'];
                $conn = new PDO(
                    $conStr,
                    $aGlobalConfigs['db']['username'],
                    $aGlobalConfigs['db']['password']
                );
                break;
            case 'pgsql':
                $conStr = 'pgsql:' .
                          'dbname=' . $aGlobalConfigs['db']['database'] . ';' .
                          'user=' . $aGlobalConfigs['db']['username'] . ';' .
                          'password=' . $aGlobalConfigs['db']['password'] . ';' .
                          'host=' . $aGlobalConfigs['db']['hostname'] . ';' .
                          'port=' . $aGlobalConfigs['db']['connport'] . ';' .
                          'charset=' . $aGlobalConfigs['db']['charset'];
                $conn = new PDO($conStr);
                break;
        }
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $conn;
        } catch (PDOException $e) {
            self::errorHandler($e->getMessage());
            exit();
        }
    }

    /**
     * Verifica se a extensão PDO está carregada.
     */
    public static function checkPdoExtension()
    {
        if (! extension_loaded('PDO')) {
            throw new RuntimeException(
                'A extensão PDO é necessária, mas não está carregada!'
            );
        }
    }

    /**
     * Método para tratamento de erro em caso de falha na conexão com o bd.
     *
     * @param string $error Mensagem de erro
     */
    public static function errorHandler($error)
    {
        if (! self::ERROR_SUPRESS) {
            print_r($error);
        }
    }

}
