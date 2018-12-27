<?php

/**
 * @package MyFramework
 * @subpackage Log
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Log;

/**
 * Fornece uma interface abstrata para definição de algoritmos de LOG.
 * Esta classe irá implementar o Design Pattern Strategy, ou seja, uma família
 * de algoritimos encapsulados em uma hierarquia de objetos.
 * Como esta é a superclasse, será uma classe abstrata contendo os métodos que
 * os algoritmos filhos devem prover.
 */
abstract class Logger
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * Instancia um logger.
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        file_put_contents($filename, '');
    }

    /**
     * Classe abstrata para forçar o método write a ter implementação
     * obrigatória nas classes filhas.
     *
     * @param string $message Mensagem de LOG
     */
    abstract function write($message);
}
