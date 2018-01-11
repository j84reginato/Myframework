<?php

namespace sys\core;

class Language
{
    private $language;
    private $available_languages;

    /**
     * getLanguage
     * 
     * Retorna o valor do atributo Language->language
     * 
     * @return str - O idioma selecionado
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * getLanguages
     * 
     * Retorna o valor do atributo Language->available_languages
     * 
     * @return array - Um array contendo os idiomas disponíveis
     */
    public function getAvailableLanguages()
    {
        return $this->available_languages;
    }

    /**
     * setLanguage
     * 
     * Configura o idioma do sistema (atributo Language->language)
     * 
     * @global \KdDoctor\classes\Database $objDb - Objeto conexão PDO
     * @global \KdDoctor\classes\User $objUser - Objeto usuário
     * @global \KdDoctor\classes\System $objSystem - Objeto sistema
     */
    public function setLanguage()
    {
        global $objDb;
        global $objUser;
        global $objSystem;

        $get_lan = filter_input(INPUT_GET, 'lan');
        $cookie_user_language = filter_input(INPUT_COOKIE, 'USER_LANGUAGE');

        // Se for submetido o valor 'lan' através do método GET
        if (isset($get_lan) && !empty($get_lan)) {
            $this->language = preg_replace("/[^a-zA-Z_]/", '', $get_lan);
            if ($objUser->getLoggedIn()) {
                $query = "UPDATE ". DB_PREFIX . "users SET language = :language WHERE id = :user_id";
                $params[] = array(':language', $this->language, 'str');
                $params[] = array(':user_id', $objUser->getUserData('id'), 'int');
                $objDb->query($query, $params);
            } else {
                setcookie('USER_LANGUAGE', $this->language, time() + 31536000, '/');
            }
        // Ou caso o usuário estiver logado
        } elseif ($objUser->getLoggedIn()) {
            $this->language = $objUser->getUserData('language');
        // Ou se houver um cookie na memória
        } elseif (isset($cookie_user_language)) {
            $this->language = preg_replace("/[^a-zA-Z_]/", '', $cookie_user_language);
        }
        
        // Em último caso, usa o idioma padrão do sistema
        if (!isset($this->language) || empty($this->language)) {
            $this->language = $objSystem->getSettings('default_language');
        }

        /* Inclui o arquivo que contém o charset, a variável de indicação da direção do texto,
         * as mensagens de interface com o usuário e mensagens de erro.
         * No idioma selecionado anteriormente.
         */
        include MAIN_PATH . 'language/' . $this->language . '/messages.inc.php';

        // Procura pelos idiomas instalados
        $this->setAvailableLanguages();
        
        // Verifica se o idioma existe
        if (!in_array($this->language, $this->available_languages)) {
            $this->language = $objSystem->getSettings('default_language');
        }
    }

    /**
     * setAvailableLanguages
     * 
     * Configura o atributo Language->available_languages
     * 
     */
    private function setAvailableLanguages()
    {
        $this->available_languages = array();
        $handle = opendir(MAIN_PATH . 'language');
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ('.' != $file && '..' != $file) {
                    if (preg_match('/^([a-zA-Z_]{2,})$/i', $file)) {
                        $this->available_languages[$file] = $file;
                    }
                }
            }
        }
        closedir($handle);
    }
    
    /**
     * getLanguageImage
     *
     * @global \KdDoctor\classes\System $objSystem
     * @param str $string - Nome do arquivo de imagem
     * @return str - O endereço completo do arquivo de imagem
     */
    public function getLanguageImage($string)
    {
        global $objSystem;
        return $objSystem->getSettings('site_url') . 'language/' . $this->language . '/images/' . $string;
    }
}
