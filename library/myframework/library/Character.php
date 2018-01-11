<?php

namespace sys\core;

class Character
{
    private $alphanumeric;

    /**
     * setAlphanumeric
     *
     * Retira caracteres não alfa-numéricos da string e retorna a string formatada
     *
     * @param string $str - String a ser formatada
     * @return string - String devidamente formatada
     */
    public function setAlphanumeric($str)
    {
        $this->alphanumeric = preg_replace("/[^a-zA-Z0-9\s]/", '', $str);
        return $this->alphanumeric;
    }

    /**
     * cleanVars
     *
     * @param type $input
     * @param type $allow_html
     * @return type
     */
    function cleanVars($input, $allow_html = false)
    {
        $config = array('elements' => '-*');

        if ($allow_html) {
            $config = array(
                'safe' => 1,
                'elements' => 'a, ol, ul, li, u, strong, em, br, p',
                'deny_attribute' => '* -href'
                );
        }
        return str_replace(array('&lt;', '&gt;', '&amp;'), array('<', '>', '&'), htmLawed($input, $config));
    }
}
