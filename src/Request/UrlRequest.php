<?php

/**
 * @package MyFramework
 * @category Request
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Request;

/**
 * Classe responsável por tratar a requisição url do usuário.
 */
abstract class UrlRequest
{
    /**
     * Array em que cada chave representa um segmento da url requisitada.
     *
     * @var array
     */
    protected $aUrl;

    /**
     * Configura o valor do atributo.
     */
    protected function setUrlArray()
    {
        $sUrlRequest = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
        $sUrl = isset($sUrlRequest) ? $sUrlRequest : 'home/index';
        $this->aUrl = explode('/', $sUrl);
    }

}
