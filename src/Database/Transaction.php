<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

use j84Reginato\MyFramework\Log\Logger;
use PDO;

/**
 * Fornece os métodos necessários para manipular transações.
 * Ao realizarmos uma série de operações com o banco de dados, uma transação
 * permite que as operações sejam realizadas em bloco, ou seja, que todas as
 * operações sejam realizadas ou que todas as operações sejam desfeitas quando
 * da ocorrência de uma falha (exceção) por um motivo qualquer no meio do
 * processo.
 *
 * Uma transação apresenta algumas propriedades conhecidas como ACID:
 * - Atomicidade;
 * - Consistência;
 * - Isolamento e
 * - Durabilidade.
 *
 * Não deve ser instanciada, pois possui somente atributos e métodos estáticos
 */
class Transaction
{
    /**
     * @var type
     */
    private static $conn;

    /**
     * @var type
     */
    private static $logger;

    /**
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que nova instância desta classe seja
     * criada exteranamente à classe através do operador "new".
     */
    private function __construct()
    {
    }

    /**
     * Abre uma transação.
     */
    public static function open()
    {
        if (empty(self::$conn)) {
            self::$conn = Connection::open();
            self::$conn->beginTransaction();
            self::$logger = null;
        }
    }

    /**
     * Retorna a conexão ativa da transação.
     *
     * @return PDO Objeto PDO
     */
    public static function get()
    {
        return self::$conn;
    }

    /**
     * Finaliza uma transação.
     *
     * Se o parâmetro recebido for "true" realiza um commit, ou seja, aplica as
     * operações realizadas durante a transação.
     *
     * Se o parâmetro recebido for "false" realiza um rollback, ou seja, desfaz
     * as operações realizadas durante a transação.
     *
     * @param boolean $success Resultado das operações no banco de dados
     */
    public static function close($success)
    {
        if (self::$conn) {
            if ($success) {
                self::$conn->commit();
            } else {
                self::$conn->rollback();
            }
            self::$conn = null;
        }
    }

    /**
     * Define qual estratégia (algoritmo de LOG será usado).
     *
     * @param Logger $logger Uma instancia da classe Logger
     */
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }

    /**
     * Armazena uma mensagem no arquivo de LOG de acordo com a estratégia
     * (txt, xml, etc.), definida no método setLogger.
     *
     * @param string $message Mensagem a ser gravada
     */
    public static function log($message)
    {
        if (self::$logger) {
            self::$logger->write($message);
        }
    }

}
