<?php

namespace app\object;

use sys\core\Object;

class Search extends Object
{
    private $action;
    private $type;
    private $category;
    private $city;
    private $date;
    private $page;
    private $order_by;
    private $order_type;
    private $next_order_type;
    private $order_arrow;
    private $errors;
    private $error_message;
    private $where;
    private $params;
    private $total;
    private $pages;
    private $offset;
    private $prev;
    private $next;

    /**
     * setAction
     * Configura o valor do atributo
     *
     * @param string $action
     */
    private function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * getAction
     * Retorna o valor do atributo
     *
     * @return string
     */
    private function getAction()
    {
        return $this->action;
    }

    /**
     * setType
     * Configura o valor do atributo
     *
     * @param string $type
     */
    private function setType($type)
    {
        $this->type = $type;
    }

    /**
     * getType
     * Retorna o valor do atributo
     * Trata-se do tipo de serviço selecionado (especialidades médicas, exames laboratoriais ou de imagem)
     *
     * @return string
     */
    private function getType()
    {
        return $this->type;
    }

    /**
     * setCategory
     * Configura o valor do atributo
     *
     * @param integer $category
     */
    private function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * getCategory
     * Retorna o valor do atributo
     * Trata-se do valor da chave do item selecionado na caixa de seleção dos serviços
     *
     * @return integer
     */
    private function getCategory()
    {
        return $this->category;
    }

    /**
     * setCity
     * Configura o valor do atributo
     *
     * @param string $city
     */
    private function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * getCity
     * Retorna o valor do atributo
     * Trata-se do valor da chave do item selecionado na caixa de seleção das cidades
     *
     * @return string
     */
    private function getCity()
    {
        return $this->city;
    }

    /**
     * setDate
     * Configura o valor do atributo
     *
     * @param string $date
     */
    private function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * getDate
     * Retorna o valor do atributo
     *
     * @return string
     */
    private function getDate()
    {
        return $this->date;
    }

    /**
     * setPage
     * Configura o valor do atributo Search->page
     *
     * @param integer $page
     */
    private function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * getPage
     * Retorna o valor do atributo Search->page
     *
     * @return integer
     */
    private function getPage()
    {
        return $this->page;
    }

    /**
     * getOrderBy
     * Retorna o valor do atributo Search->order_by
     *
     * @return string
     */
    private function getOrderBy()
    {
        return $this->order_by;
    }

    /**
     * getOrderType
     * Retorna o valor do atributo Search->order_type
     *
     * @return string
     */
    private function getOrderType()
    {
        return $this->order_type;
    }

    /**
     * getNextOrderType
     * Retorna o valor do atributo Search->next_order_type
     *
     * @return string
     */
    private function getNextOrderType()
    {
        return $this->next_order_type;
    }

    /**
     * getOrderArrow
     * Retorna o valor do atributo Search->order_arrow
     *
     * @return string
     */
    private function getOrderArrow()
    {
        return $this->order_arrow;
    }

    /**
     * getErrors
     * Retorna o valor do atributo Search->errors
     *
     * @return array()
     */
    private function getErrors()
    {
        return $this->errors;
    }

    /**
     * getErrorMessage
     * Retorna o valor do atributo Search->error_message
     *
     * @return type
     */
    private function getErrorMessage()
    {
        $this->setErrorMessage();
        return $this->error_message;
    }

    /**
     * getWhere
     * Retorna o valor do atributo Search->where
     *
     * @return str
     */
    private function getWhere()
    {
        return $this->where;
    }

    /**
     * getParams
     * Retorna o valor do atributo Search->params
     *
     * @return array()
     */
    private function getParams()
    {
        return $this->params;
    }

    /**
     * getTotal
     * Retorna o valor do atributo Search->total
     *
     * @return integer
     */
    private function getTotal()
    {
        return $this->total;
    }

    /**
     * getPages
     * Retorna o valor do atributo Search->pages
     *
     * @return integer
     */
    private function getPages()
    {
        return $this->pages;
    }

    /**
     * getOffset
     * Retorna o valor do atributo Search->offset
     *
     * @return integer
     */
    private function getOffset()
    {
        return $this->offset;
    }

    /**
     * getPrev
     * Retorna o valor do atributo Search->prev
     *
     * @return integer
     */
    private function getPrev()
    {
        return $this->prev;
    }

    /**
     * getNext
     * Retorna o valor do atributo Search->next
     *
     * @return integer
     */
    private function getNext()
    {
        return $this->next;
    }

    /**
     * setOrderBy
     * Configura o valor do atributo Search->order_by
     *
     * @param string $order_by
     */
    private function setOrderBy($order_by)
    {
        $this->order_by = $order_by;
    }

    /**
     * setOrderType
     * Configura o valor do atributo Search->order_type
     *
     * @param string $order_type
     */
    private function setOrderType($order_type)
    {
        $this->order_type = $order_type;
    }

    /**
     * setNextOrderType
     * Configura o valor do atributo Search->next_order_type
     *
     * @param string $next_order_type
     */
    private function setNextOrderType($next_order_type)
    {
        $this->next_order_type = $next_order_type;
    }

    /**
     * setOrderArrow
     * Configura o valor do atributo Search->order_arrow
     *
     * @param string $order_arrow
     */
    private function setOrderArrow($order_arrow)
    {
        $this->order_arrow = $order_arrow;
    }

    /**
     * setErrors
     * Configura o valor do atributo Search->errors
     *
     * @param string $errors - Mensagem de erro
     */
    private function setErrors($errors)
    {
        $this->errors[] = $errors;
    }

    /**
     * setErrorMessage
     * Configura o valor do atributo Search->error_message
     */
    private function setErrorMessage()
    {
        $this->error_message = '';
        if (count($this->getErrors()) > 0) {
            foreach ($this->errors as $error) {
                $this->error_message = $this->error_message . $error . '<br>';
            }
        }
    }

    /**
     * setWhere
     * Configura o valor do atributo Search->where
     *
     * @param string $where
     */
    private function setWhere($where)
    {
        $this->where = $where;
    }

    /**
     * setParams
     * Configura o valor do atributo Search->params[]
     *
     * @param array() $params
     */
    private function setParams($params)
    {
        $this->params[] = $params;
    }

    /**
     * setTotal
     * Configura o valor do atributo Search->total
     *
     * @param integer $total
     */
    private function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * setPages
     * Configura o valor do atributo Search->pages
     *
     * @param integer $pages
     */
    private function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * setOffset
     * Configura o valor do atributo Search->offset
     *
     * @param integer $offset
     */
    private function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * setPrev
     * Configura o valor do atributo Search->prev
     *
     * @param integer $prev
     */
    private function setPrev($prev)
    {
        $this->prev = $prev;
    }

    /**
     * setNext
     * Configura o valor do atributo Search->next
     *
     * @param integer $next
     */
    private function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * __construct
     * Este método construtor irá configurar os valores dos atributos, dados de entrada (GETs) e alocar dados nas variáveis SESSION
     */
    public function __construct()
    {
        $_SESSION['search']['date'] = isset($_SESSION['search']['date']) ? $_SESSION['search']['date'] : date('Y-m-d');
        $_SESSION['search']['order_by'] = isset($_SESSION['search']['order_by']) ? $_SESSION['search']['order_by'] : 'name';
        $_SESSION['search']['order_type'] = isset($_SESSION['search']['order_type']) ? $_SESSION['search']['order_type'] : 'ASC';
        $_SESSION['search']['next_order_type'] = ($_SESSION['search']['order_type'] == 'ASC') ? 'DESC' : 'ASC';
        $_SESSION['search']['order_arrow'] = ($_SESSION['search']['order_type'] == 'ASC') ? '<img src="images/_arrow_up.gif" align="center"/>' : '<img src="images/_arrow_down.gif" align="center"/>';

        //$this->setInputs();
        //$this->setSession();
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    /**
     * buildParams
     *
     * Chama os métodos construtores dos parâmetros da consulta
     * - Categoria;
     * - Localização;
     * - Data desejada.
     */
    private function buildParams()
    {
        $this->setCategoryParam();
        $this->setCityParam();
        $this->setDateParam();
    }

    /**
     * setCategoryParam
     *
     * Configura o parâmetro categoria para a pesquisa
     *
     * @global object $objCategory
     */
    private function setCategoryParam()
    {
        global $objCategory;
        global $objUserMessage;

        if (!empty($this->category)) {
            $parentNode = $objCategory->getParentNode($this->type, $this->category);
            $children = $objCategory->getChildrenList($parentNode['left_id'], $parentNode['right_id'], $this->type);
            $child_array = array($this->category);
            foreach ($children as $k => $v) {
                $child_array[] = $v['id'];
            }
            $categoryList = '(';
            $categoryList .= implode(',', $child_array);
            $categoryList .= ')';
            $this->setWhere("(services." . $this->type . " IN " . $categoryList . ") AND ");
        } else {
            $objUserMessage->setError('Por favor, selecione uma categoria!');
        }
    }

    /**
     * searchForCity
     *
     * Configura o parâmetro cidade para a pesquisa
     */
    private function setCityParam()
    {
        if (!empty($this->city)) {
            $this->setWhere($this->getWhere() . "(services.city = :city) AND ");
            $this->setParams(array(':city', $this->getCity(), 'str'));
        } else {
            $this->setErrors('Por favor, selecione uma cidade!');
        }
    }

    /**
     * setDateParam
     *
     * Configura o parâmetros data para a pesquisa
     */
    private function setDateParam()
    {
        //$this->where .= "(services.date = :date) AND ";
        //$this->params[] = array(':date', $this->date, 'str');
    }

    /**
     * checkTotalServices
     *
     * Configura o número de registros encontrados na pesquisa (parâmetro $total)
     *
     * @global \KdDoctor\classes\Services $objServices
     */
    private function checkTotalServices()
    {
        global $objServices;

        $this->setWhere("suspended = 0 AND " . $this->getWhere() . " start_date <= :date");
        $this->setParams(array(':date', $this->getDate(), 'str'));

        $this->setTotal($objServices->getNumberOfServices($this->getWhere(), $this->getParams()));

        if (!$this->getTotal() || !is_numeric($this->getTotal())) {
            $this->setErrors('Infelizmente não há nenhum profissional/cliníca disponível no momento');
        }
    }

    /**
     * hasError
     *
     * Verifica se houve erros
     *
     * @return boolean
     */
    private function hasError()
    {
        if (count($this->errors) == 0) {
            return false;
        }
        return true;
    }

    /**
     * calculatePages
     *
     * Calcula o número de páginas necessárias para apresentar todos os itens retornados na consulta (parâmetro $pages).
     *
     * @global \KdDoctor\classes\System $objSystem
     */
    private function calcNeedPages()
    {
        global $objSystem;
        $this->setPages(($this->getTotal() == 0) ? 1 : ceil($this->getTotal() / $objSystem->getSettings('per_page')));
    }

    /**
     * setPageToShow
     *
     * Avalia qual página mosrar
     *
     * @global \KdDoctor\classes\System $objSystem
     */
    private function setPageToShow()
    {
        global $objSystem;

        if (($this->getPage()) != null || intval($this->getPage()) <= 1 || empty($this->getPage())) {
            $this->setPage(1);
            $this->setOffset(0);
        } else {
            $this->setPage(intval($this->getPage()));
            $this->setOffset(($this->getPage() - 1) * $objSystem->getSettings('per_page'));
        }
    }

    /**
     * setPageLinks
     *
     * Gera links de paginação
     * Configura os atributos $prev e $next
     *
     * @global \KdDoctor\classes\System $objSystem
     * @global \KdDoctor\classes\Template $objTemplate
     */
    private function setPageLinks()
    {
        global $objSystem;
        global $objTemplate;

        $this->setPrev(intval($this->getPage() - 1));
        $this->setNext(intval($this->getPage() + 1));
        if ($this->getPages() > 1) {
            $low = $this->getPage() - 5;
            if ($low <= 0) {
                $low = 1;
            }
            $counter = $low;
            while ($counter <= $this->getPages() && $counter < ($this->getPage() + 6)) {
                $objTemplate->assignBlockVars('pages', array(
                    'page' => (($this->getPage() == $counter)
                        ? '<a href="#"><u><b>' . $counter . '</b></u></a>'
                        : '<a href="' . $objSystem->getSettings('site_url') . 'index.php?action=search&page='. $counter .'"><u>'. $counter .'</u></a>'
                    )
                ));
                $counter++;
            }
        }
    }

    /**
     * assingnTemplateVars
     *
     * Atribui valores às variáveis do template
     *
     * @global \KdDoctor\classes\Template $objTemplate
     * @global \KdDoctor\classes\Language $objLanguage
     * @param boolean $searching
     */
    private function assingnTemplateVars($searching)
    {
        global $objTemplate;
        global $objLanguage;
        global $objSystem;

        if ($searching) {
            $objTemplate->assignVars(array(
                'page_title' => 'Busca',
                'num_services' => (is_numeric($this->getTotal()) && $this->getTotal() > 0) ? $this->getTotal() : '',
                'order_next' => $this->getNextOrderType(),
                'order_col' => $this->getOrderBy(),
                'next_order_img' => $this->getOrderArrow(),
                'are_items' => ($this->getTotal() > 0),
                'bg_colour' => (!($this->getTotal() % 2)) ? '' : 'class="alt-row"',
                'records' => $this->getTotal(),
                'page' => $this->getPage(),
                'pages' => $this->getPages(),
                'prev' => ($this->getPage() > 1)
                    ? '<a href="' . $objSystem->getSettings('site_url') . 'index.php?page=' . $this->getPrev() . '"><u>' . "&lt;&lt;Anterior" . '</u></a>'
                    : '<a href="#"><u>' . "&lt;&lt;Anterior" . '</u></a>',
                'next' => ($this->getPage() < $this->getPages())
                    ? '<a href="' . $objSystem->getSettings('site_url') . 'index.php?page=' . $this->getNext() . '"><u>' . "Próxima&gt;&gt;" . '</u></a>'
                    : '<a href="#"><u>' . "Próxima&gt;&gt;" . '</u></a>'
            ));
        } else {
            include MAIN_PATH . 'language/' . $objLanguage->getLanguage() . '/med_spcts.inc.php';
            include MAIN_PATH . 'language/' . $objLanguage->getLanguage() . '/lab_exams.inc.php';
            include MAIN_PATH . 'language/' . $objLanguage->getLanguage() . '/img_exams.inc.php';
            $objCity = new \KdDoctor\classes\City();
            $objTemplate->assignVars(array(
                'error_message' => $this->getErrorMessage(),
                'user_type' => $_SESSION['user_type'],
                'med_spcts' => $med_spcts_plain,
                'lab_exams' => $lab_exams_plain,
                'img_exams' => $img_exams_plain,
                'cities' => $objCity->getAvailableCities()
            ));
            unset($_SESSION['search']);
        }
    }

    /**
     * generateHtmlPage
     *
     * Gera o HTML da página de resultados da busca
     *
     * @global \KdDoctor\classes\Template $objTemplate
     * @param boolean $searching
     */
    private function generateHtmlPage($searching)
    {
        global $objTemplate;

        if ($searching) {
            include 'header.php';
            $objTemplate->setFilenames(array('body' => 'search_result.tpl'));
            $objTemplate->display('body');
            include 'footer.php';
        } else {
            $page_to_show = ((!isset($_SESSION['user_type']) || $_SESSION['user_type'] == '' || $_SESSION['user_type'] == 'patient')
                ? 'home_patient.tpl'
                : 'home_provider.tpl'
            );
            $objTemplate->setFilenames(array('body' => $page_to_show));
            $objTemplate->display('body');
        }
    }

}