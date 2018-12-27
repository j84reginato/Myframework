<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category View
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\View;

use const APP_ACTION;
use const APP_CONTROLLER;
use const APP_MODULE;
use const APP_PATH;

/**
 * Classe responsável pela configuração da camada View.
 */
class View
{
    /**
     * Cte que representa o título padrão da página HTML a ser renderizada.
     */
    const DEFAULT_TITLE = 'Sistema de Atendimento Básico';

    /**
     * Cte que representa a descrição padrão da página HTML a ser renderizada.
     */
    const DEFAULT_DESCRIPTION = ''
        . 'Esta aplicação trata-se de um protótipo de sistema de atendimento '
        . 'básico desenvolvido com o objetivo de contemplar uma etapa do '
        . 'Processo Seletivo para Analista de Desenvolvimento FullStack PHP da '
        . 'empresa Madeira Madeira.';

    /**
     * Cte que representa as palavras-chave padrão da página a ser renderizada.
     */
    const DEFAULT_KEYWORDS = ''
        . 'atendimento, '
        . 'MVC+S, '
        . 'Factory, '
        . 'Dependency Injection, '
        . 'Madeira Madeira';

    /**
     * Representa o nome do arquivo .phtml a ser renderizado.
     *
     * @var string
     */
    private $sRenderFile;

    /**
     * Representa o(s) caminho(s) do(s) arquivo(s) .phtml a ser(em) renderizado(s).
     *
     * @var string|array
     */
    private $mRenderPath;

    /**
     * Representa o nome do arquivo de layout da página a ser renderizada.
     *
     * @var string
     */
    private $sLayout = 'layout';

    /**
     * Representa o título da página HTML a ser renderizada.
     *
     * @var string
     */
    private $sTitle;

    /**
     * Representa a descrição da página HTML a ser renderizada.
     *
     * @var string
     */
    private $sDescription;

    /**
     * Representa as palavras-chave da página HTML a ser renderizada.
     *
     * @var string
     */
    private $sKeywords;

    /**
     * Representa uma única instancia desta clase (Singleton instance).
     *
     * @var self
     */
    private static $oInstance;

    /**
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que uma instância desta classe seja
     * criada externamente à classe através do operador "new".
     */
    private function __construct()
    {
        $this->sModule = APP_MODULE;
        $this->sController = APP_CONTROLLER;
        $this->sAction = APP_ACTION;
    }

    /**
     * Método clone do tipo privado previne a clonagem da instância da classe.
     */
    private function __clone()
    {
    }

    /**
     * Método unserialize do tipo privado para prevenir a desserialização da
     * instância dessa classe.
     */
    private function __wakeup()
    {
    }

    /**
     * Retorna uma instância única desta classe (Singleton instance).
     *
     * @return self
     */
    public static function getInstance()
    {
        if (empty(self::$oInstance)) {
            self::$oInstance = new self;
        }
        return self::$oInstance;
    }

    /**
     * Configura o valor do atributo.
     *
     * @param string $sLayout
     */
    public function setLayout($sLayout)
    {
        $this->sLayout = $sLayout;
    }

    /**
     * Configura o valor do atributo.
     *
     * @param string $sTitle
     */
    public function setTitle($sTitle)
    {
        $this->sTitle = $sTitle;
    }

    /**
     * Configura o valor do atributo.
     *
     * @param string $sDescription
     */
    public function setDescription($sDescription)
    {
        $this->sDescription = $sDescription;
    }

    /**
     * Configura o valor do atributo.
     *
     * @param string $sKeywords
     */
    public function setKeywords($sKeywords)
    {
        $this->sKeywords = $sKeywords;
    }

    /**
     * Configura o valor dos atributo do arquivo de visualização.
     *
     * @param array $aData
     * @return boolean
     */
    public function setData($aData)
    {
        if (! is_array($aData) || ! count($aData)) {
            return false;
        }

        foreach ($aData as $sKey => $mValue) {
            $this->{$sKey} = $mValue;
        }
    }

    /**
     * Método que deverá ser chamado pelo Controller caso se deseje como
     * resposta o envio de dados no formato JSON.
     *
     * @param mixed $aData
     */
    public function jsonView($aData)
    {
        header('Content-Type: application/json');
        echo json_encode($aData);
        exit();
    }

    /**
     * Método que deverá ser chamado pelo Controller caso se deseje como
     * resposta o processamento de uma página HTML.
     *
     * Este método permite:
     * 1. Configurar a tag title e as meta-tags: description e keywords;
     * 2. Configurar o(s) caminho(s) do(s) arquivo(s) .phtml requisitado(s);
     * 3. Enviar o layout ou o(s) arquivo(s) de visualização para renderização.
     *
     * @param string|array $mRender
     */
    public function htmlView($mRender = null)
    {
        $this->sTitle = is_null($this->sTitle) ? self::DEFAULT_TITLE : $this->sTitle;
        $this->sDescription = is_null($this->sDescription) ? self::DEFAULT_DESCRIPTION : $this->sDescription;
        $this->sKeywords = is_null($this->sKeywords) ? self::DEFAULT_KEYWORDS : $this->sKeywords;

        $this->setPath($mRender);

        if (! is_null($this->sLayout)) {
            $this->sLayout = APP_PATH . "{$this->sModule}/view/_layout/{$this->sLayout}.phtml";
            $this->fileExists($this->sLayout);
            $this->render($this->sLayout);
            return true;
        }

        $this->render();
    }

    /**
     * Configura o(s) caminho(s) do(s) arquivo(s) de visualização solicitado(s)
     * pelo Controller para que ser(em) renderizado(s) pela aplicação.
     *
     * @param mixed $mRender
     */
    private function setPath($mRender)
    {
        if (! is_array($mRender)) {
            $this->sRenderFile = is_null($mRender) ? $this->sAction : $mRender;
            $this->mRenderPath = APP_PATH . "{$this->sModule}/view/{$this->sController}/{$this->sRenderFile}.phtml";
            $this->fileExists($this->mRenderPath);
            return true;
        }

        foreach ($mRender as $sFile) {
            $sPath = APP_PATH . "{$this->sModule}/view/{$this->sController}/{$sFile}.phtml";
            $this->fileExists($sPath);
            $this->mRenderPath[] = $sPath;
        }
    }

    /**
     * Renderiza o arquivo de visualização correspondente.
     *
     * @param mixed $mFile
     */
    public function render($mFile = null)
    {
        // Caso o layout tenha requisitado renderizar um array de arquivos.
        if (! is_null($mFile) && is_array($mFile)) {
            $this->renderFilesArray($mFile);
            return true;
        }

        // Caso seja a renderização do layout ou o layout tenha requisitado
        // renderizar um único arquivo.
        if (! is_null($mFile)) {
            $this->fileExists($mFile);
            include $mFile;
            return true;
        }

        // Caso o Controller tenha requisitado renderizar um array de arquivos.
        if (is_null($mFile) && is_array($this->mRenderPath)) {
            $this->renderFilesArray($this->mRenderPath);
            return true;
        }

        // Caso o Controller tenha requisitado renderizar um único arquivo.
        include $this->mRenderPath;
    }

    /**
     * Renderiza cada arquivo .phtml contido no array recebido.
     *
     * @param array $aFile
     */
    private function renderFilesArray($aFile)
    {
        foreach ($aFile as $sFile) {
            $this->fileExists($sFile);
            include $sFile;
        }
    }

    /**
     * Verifica se o arquivo informado existe.
     *
     * @param string $sFile
     * @return boolean
     */
    private function fileExists($sFile)
    {
        if (! file_exists($sFile)) {
            header('HTTP/1.0 404 Not Found');
            define(
                "ERROR",
                "Não foi possível localizar o arquivo {$sFile}!"
            );
            include APP_PATH . "/{$this->sModule}/view/_error/404_error.phtml";
            exit();
        }
        return true;
    }

}
