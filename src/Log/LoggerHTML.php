<?php

/**
 * @package MyFramework
 * @subpackage Log
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Log;

/**
 * Implementa o algoritmo de LOG em HTML
 */
class LoggerHTML extends Logger
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
        $text = "<p>\n";
        $text .= "   <b>$time</b> : \n";
        $text .= "   <i>$message</i> <br>\n";
        $text .= "</p>\n";

        // Adiciona ao final do arquivo
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }

}
