<?php

namespace Myframework\Log;

/**
 * Implementa o algoritmo de LOG para ser gravado no banco de dados
 * 
 * @author Jonatan Noronha Reginato
 */
class LogSQL
{
    /**
     * Insere um registro de log no banco de dados.
     *
     * @global \KdDoctor\classes\DatabasePdo $objDb - Objeto conexão PDO
     * @param str $type - Possíveis tipos cron, erro, administador, paciente, prestador, modificação
     * @param str $message - Descrição do log
     * @param int $action_id - Ação que estva sendo realizada (padrão = 0)
     * @param int $user_id - Código do usuário, se logado (padrão = 0)
     * @param str $user_ip - Endereço IP do usuário
     */
    public function write($type, $message, $action_id = 0, $user_id = 0, $user_ip = USER_IP)
    {
        global $objDb;

        $query =  "INSERT INTO " . DB_PREFIX . "logs "
                    . "(type, message, action_id, user_id, user_ip, timestamp) "
                . "VALUES "
                    . "(:type, :message, :action_id, :user_id, :user_ip, :time)";

        $params[] = array(':type', $type, 'str');
        $params[] = array(':message', $message, 'str');
        $params[] = array(':action_id', $action_id, 'int');
        $params[] = array(':user_id', $user_id, 'int');
        $params[] = array(':user_ip', $user_ip, 'str');
        $params[] = array(':time', time(), 'int');
        $objDb->query($query, $params);
    }
}
