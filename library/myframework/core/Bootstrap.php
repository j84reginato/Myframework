<?php

namespace sys\core;

abstract class Bootstrap
{
    public $sys;

    public function setup()
    {
        // Realiza a conexão com o Banco de Dados
        $objDb = Connection::getConnection();
        $this->sys->objDb = $objDb;

        // Instancia os objetos
        //$objLog = new KdDoctor\classes\Log();
        //$this->sys->objDb = $objLog;

        $objError = new \core\ErrorHandler();
        $this->sys->objError = $objError;

        $objCharacter = new Character();
        $this->sys->objCharacter = $objCharacter;

        $objDate = new Dates();
        $this->sys->objDate = $objDate;

        $objLanguage = new Language();
        $this->sys->objLanguage;

        $objTemplate = new Template();
        $this->sys->objTemplate = $objTemplate;

        $objCounter = new Counter();
        $this->sys->objCounter = $objCounter;

        $objTimezone = new UserTimeZone();
        $this->sys->objTimezone = $objTimezone;

        $objInput = new Input();
        $this->sys->objInput = $objInput;

        $objUserMessage = new UserMessage();
        $this->sys->objUserMessage = $objUserMessage;

        // Chama os métodos que definirá o "idioma" e estilo do "template"
        $objLanguage->setLanguage();

        $objTemplate->setTemplate();

    }
}
