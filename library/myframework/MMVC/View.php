<?php

namespace Myframework\MMVC;

class View extends Controller
{
    public $data;
    public $layout = 'layout';

    private $render_path;
    private $render_file;

    protected $title = null;
    protected $description;
    protected $keywords;
    protected $image;
    protected $caption_controller;
    protected $caption_method;
    protected $caption_params;

    const DEFAULT_TITLE = '';
    const DEFAULT_DESCRIPTION = '';
    const DEFAULT_KEYWORDS = '';

    /**
     * view
     *
     * @param string $render
     */
    protected function view($render = null)
    {
        $this->title = is_null($this->title) ? self::DEFAULT_TITLE : $this->title;
        $this->description = is_null($this->description) ? self::DEFAULT_DESCRIPTION : $this->description;
        $this->keywords = is_null($this->keywords) ? self::DEFAULT_KEYWORDS : $this->keywords;

        $this->setPath($render);
        if (is_null($this->layout)) {
            $this->render();
        } else {
            $this->layout = "application/modules/{$this->module}/views/_layout/{$this->layout}.php";
            if (file_exists($this->layout)) {
                $this->render($this->layout);
            } else {
                die('Não possível localizar o layout');
            }
        }
    }

    /**
     * setPath
     *
     * @param mixed $render - Array ou string dos arquivos a serem renderizados
     */
    private function setPath($render)
    {
        if (is_array($render)) {
            foreach ($render as $file) {
                $path = "application/modules/{$this->enviroment}/views/{$this->controller}/{$file}.php";
                $this->fileExists($path);
                $this->render_path[] = $path;
            }
        } else {
            $this->render_file = is_null($render) ? $this->method : $render;
            $this->render_path = "application/modules/{$this->enviroment}/views/{$this->controller}/{$this->render_file}.php";
            $this->fileExists($this->render_path);
        }
    }

    /**
     * render
     *
     * @param mixed $file - Array ou string dos arquivos a serem renderizados
     */
    public function render($file = null)
    {
        if (is_array($this->data) && count($this->data) > 0) {
            extract($this->data, EXTR_PREFIX_ALL, 'view');
            extract(array(
                'controller' => is_null($this->caption_controller) ? '' : $this->caption_controller,
                'method' => is_null($this->caption_method) ? '' : $this->caption_method,
                'params' => is_null($this->caption_params) ? '' : $this->caption_params
                ), EXTR_PREFIX_ALL, 'caption'
            );
        }
        if (!is_null($file) && is_array($file)) {
            foreach ($file as $value) {
                include $value;
            }
        } elseif (is_null($file) && is_array($this->render_path)) {
            foreach ($this->render_path as $value) {
                include $value;
            }
        } else {
            $file = is_null($file) ? $this->render_path : $file;
            $this->fileExists($file) ? include $file : die($file);
        }
    }

    /**
     * fileExists
     *
     * @param string $file
     * @return boolean
     */
    private function fileExists($file)
    {
        if (!file_exists($file)) {
            die('Não foi localizado o arquivo ' . $file);
        }
        return true;
    }
}
