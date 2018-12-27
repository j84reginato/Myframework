<?php

/**
 * @package MyFramework
 * @subpackage Captcha
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Captcha;

use Gregwar\Captcha\CaptchaBuilder;

/**
 * Classe responsável por configurar uma imagem Captcha.
 */
class Captcha
{
    /**
     * Método construtor.
     *
     * @param boolean $ignoreAllEffects
     * @param boolean $distortion
     * @param integer $maxBehindLines
     * @param integer $maxFrontLines
     * @param integer $width
     * @param integer $height
     */
    public function __construct(
        $ignoreAllEffects = true,
        $distortion = false,
        $maxBehindLines = 1,
        $maxFrontLines = 1,
        $width = 150,
        $height = 40
    )
    {
        $this->oCaptchaBuilder = new CaptchaBuilder();

        $this->oCaptchaBuilder->setIgnoreAllEffects($ignoreAllEffects);
        $this->oCaptchaBuilder->setDistortion($distortion);
        $this->oCaptchaBuilder->setMaxBehindLines($maxBehindLines);
        $this->oCaptchaBuilder->setMaxFrontLines($maxFrontLines);
        $this->oCaptchaBuilder->build($width, $height);

        $_SESSION['phrase'] = $this->oCaptchaBuilder->getPhrase();
    }

}