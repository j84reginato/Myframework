<?php

namespace Myframework\Database;

/**
 * Classe abstrata contendo parametros para a conexão com o Banco de Dados.
 *
 * @author Jonatan Noronha Reginato
 */
abstract class ConnectionConfig
{
    // Configurações do Banco de Dados
    const DB_DATATYPE   = 'mysql';
    const DB_HOSTNAME   = 'localhost';
    const DB_DATABASE   = 'jnreg870_acessomedico';
    const DB_USERNAME   = 'jnreg870';
    const DB_PASSWORD   = 'bQO9269toc';
    const DB_DATAPORT   = '3306';
    const DB_CHARSET    = 'utf8';
    const DB_PREFIX     = 'acessomedico_';
    const ERROR_SUPRESS = true;
}
