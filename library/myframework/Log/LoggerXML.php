<?php

namespace Myframework\Log;

/**
 * Implementa o algoritmo de LOG em XML
 *
 * @author Jonatan Noronha Reginato
 */
class LoggerXML extends Logger
{
    // ------------------------------------------------------------------------
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
        $text = "<log>\n";
        $text .= "   <time>$time</time>\n";
        $text .= "   <message>$message</message>\n";
        $text .= "</log>\n";

        // Adiciona ao final do arquivo
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
}
