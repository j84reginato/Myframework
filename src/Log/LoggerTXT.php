<?php

/**
 * @package MyFramework
 * @subpackage Log
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Log;

/**
 * Implementa o algoritmo de LOG em TXT.
 */
class LoggerTXT extends Logger
{
    /**
     * Escreve uma mensagem no arquivo de LOG.
     *
     * @param string $message Mensagem a ser escrita
     */
    public function write($message)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $time = date("Y-m-d H:i:s");

        // Monta a string
        $text = "$time :: $message\n";

        // Adiciona ao final do arquivo
        $handler = fopen($this->filename, FOPEN_WRITE_CREATE);
        fwrite($handler, $text);
        fclose($handler);
    }

}
