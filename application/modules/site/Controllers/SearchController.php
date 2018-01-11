<?php

namespace Application\Site\Controllers;

use sys\core\Controller;

class SearchController extends Controller
{
    /**
     * indexAction
     * Configura e inicializa a página inicial
     */
    public function index()
    {
        $objUser = new \app\object\User();
        global $objTemplate;

        global $objInput;
        global $objServices;
        global $objUserMessage;

        // Verificações iniciais
        $objUser->checkLoginOnInitialPages();
        $objUser->setUserTypeOnIndex();

        // Se o usuário houver selecionado a opção prestador no modal da index.php
        if ($_SESSION['user_type'] == 'provider') {
            $objTemplate->setFilenames(array('body' => 'home_provider.tpl'));
            $objTemplate->display('body');
            exit();
        }

        // Verifica o status inicial da SESSION
        $objInput->checkSessionValue('search');

        // Configura os valores de entrada, atributos da classe e a SESSION
        $objInput->setInputs(array(
            'action' => array(INPUT_GET, 'action', FILTER_SANITIZE_STRING),
            'type' => array(INPUT_GET, 'type', FILTER_SANITIZE_STRING),
            'category' => array(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT),
            'city' => array(INPUT_GET, 'city', FILTER_SANITIZE_STRING),
            'date' => array(INPUT_GET, 'date', FILTER_SANITIZE_STRING),
            'page' => array(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT),
            'order_by' => array(INPUT_GET, 'order_by', FILTER_SANITIZE_STRING),
            'order_type' => array(INPUT_GET, 'order_type', FILTER_SANITIZE_STRING),
            'next_order_type' => array(INPUT_GET, 'next_order_type', FILTER_SANITIZE_STRING)
        ));
        $objInput->setAttributes($this, 'search');
        $objInput->setSession('search');

        /* Em caso de:
         * - click no submit,
         * - seleção do número da página ou
         * - re-ordenação dos dados da pesquisa */
        if ($this->action == 'search') {
            $this->buildParams();
            if (!$this->hasError()) {
                $this->checkTotalServices();
                if (!$this->hasError()) {
                    $this->calcNeedPages();
                    $this->setPageToShow();
                    $this->setPageLinks();
                    $objServices->searchServices($this->getWhere(), $this->getDate(), $this->getOrderBy(), $this->getOrderType(), $this->getOffset());
                    $this->assingnTemplateVars(true);
                    $this->generateHtmlPage(true);
                    exit();
                }
            }
        }
        /* Se for inicialização da página ou os parâmetros de pesquisas selecionados resultaram em nenhum médico/clínica encontrados */
        $this->assingnTemplateVars(false);
        $this->generateHtmlPage(false);
    }
}
