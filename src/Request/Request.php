<?php

/**
 * @package MyFramework
 * @category Request
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Request;

use Exception;
use j84Reginato\MyFramework\StdClass\Parameters;
use j84Reginato\MyFramework\StdClass\ParametersInterface;

/**
 * Classe responsável por tratar a requisiçõs do usuário.
 */
class Request
{
    /**
     * Representa uma única instancia desta clase (Singleton instance).
     *
     * @var self
     */
    private static $oInstance;

    /**
     * $_GET
     *
     * @var ParametersInterface
     */
    private $queryParams;

    /**
     * $_POST
     *
     * @var ParametersInterface
     */
    private $postParams;

    /**
     * $_FILES
     *
     * @var ParametersInterface
     */
    private $fileParams;

    /**
     * $_SERVER
     *
     * @var ParametersInterface
     */
    private $serverParams;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $scheme;

    /**
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que uma instância desta classe seja
     * criada externamente à classe através do operador "new".
     */
    private function __construct()
    {
        if ($_GET) {
            $this->setQuery(new Parameters($_GET));
        }
        if ($_POST) {
            $this->setPost(new Parameters($_POST));
        }
        if ($_FILES) {
            $files = $this->mapPhpFiles();
            $this->setFiles(new Parameters($files));
        }

        $this->setServer(new Parameters($_SERVER));
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
     * setQuery.
     *
     * @param ParametersInterface $query
     * @return self
     */
    public function setQuery(ParametersInterface $query)
    {
        $this->queryParams = $query;
        return $this;
    }

    /**
     * getQuery.
     *
     * @param string|null $name Nome do parâmetro a ser recuperado ou null para obter o contêiner inteiro.
     * @param mixed|null $default Valor padrão a ser usado quando o parâmetro estiver ausente.
     * @return ParametersInterface|mixed
     */
    public function getQuery($name = null, $default = null)
    {
        if ($this->queryParams === null) {
            $this->queryParams = new Parameters();
        }

        if ($name === null) {
            return $this->queryParams;
        }

        return $this->queryParams->get($name, $default);
    }

    /**
     * setPost.
     *
     * @param ParametersInterface $post
     * @return self
     */
    public function setPost(ParametersInterface $post)
    {
        $this->postParams = $post;
        return $this;
    }

    /**
     * getPost.
     *
     * @param string|null $name Nome do parâmetro a ser recuperado ou null para obter o contêiner inteiro.
     * @param mixed|null $default Valor padrão a ser usado quando o parâmetro estiver ausente.
     * @return ParametersInterface|mixed
     */
    public function getPost($name = null, $default = null)
    {
        if ($this->postParams === null) {
            $this->postParams = new Parameters();
        }

        if ($name === null) {
            return $this->postParams;
        }

        return $this->postParams->get($name, $default);
    }

    /**
     * isPost.
     * 
     * @return boolean
     */
    public function isPost()
    {
        if ($this->postParams->count()) {
            return true;
        }
        return false;
    }

    /**
     * mapPhpFiles.
     *
     * @return array
     */
    protected function mapPhpFiles()
    {
        $files = [];
        foreach ($_FILES as $fileName => $fileParams) {
            $files[$fileName] = [];
            foreach ($fileParams as $param => $data) {
                if (! is_array($data)) {
                    $files[$fileName][$param] = $data;
                } else {
                    foreach ($data as $i => $v) {
                        $this->mapPhpFileParam($files[$fileName], $param, $i, $v);
                    }
                }
            }
        }

        return $files;
    }

    /**
     * mapPhpFileParam.
     *
     * @param array $array
     * @param string $paramName
     * @param integer|string $index
     * @param string|array $value
     */
    protected function mapPhpFileParam(&$array, $paramName, $index, $value)
    {
        if (! is_array($value)) {
            $array[$index][$paramName] = $value;
        } else {
            foreach ($value as $i => $v) {
                $this->mapPhpFileParam($array[$index], $paramName, $i, $v);
            }
        }
    }

    /**
     * setFiles.
     *
     * @param ParametersInterface $files
     * @return self
     */
    public function setFiles(ParametersInterface $files)
    {
        $this->fileParams = $files;
        return $this;
    }

    /**
     * getFiles.
     *
     * @param string|null $name Nome do parâmetro a ser recuperado ou null para obter o contêiner inteiro.
     * @param mixed|null $default Valor padrão a ser usado quando o parâmetro estiver ausente.
     * @return ParametersInterface|mixed
     */
    public function getFiles($name = null, $default = null)
    {
        if ($this->fileParams === null) {
            $this->fileParams = new Parameters();
        }

        if ($name === null) {
            return $this->fileParams;
        }

        return $this->fileParams->get($name, $default);
    }

    /**
     * setServer.
     *
     * @param ParametersInterface $server
     * @return self
     */
    public function setServer(ParametersInterface $server)
    {
        $this->serverParams = $server;

        if (function_exists('apache_request_headers')) {
            $apacheRequestHeaders = apache_request_headers();
            if (! isset($this->serverParams['HTTP_AUTHORIZATION'])) {
                if (isset($apacheRequestHeaders['Authorization'])) {
                    $this->serverParams->set('HTTP_AUTHORIZATION', $apacheRequestHeaders['Authorization']);
                } elseif (isset($apacheRequestHeaders['authorization'])) {
                    $this->serverParams->set('HTTP_AUTHORIZATION', $apacheRequestHeaders['authorization']);
                }
            }
        }

        // Set headers
        $headers = [];

        foreach ($server as $key => $value) {
            if ($value || (! is_array($value) && strlen($value))) {
                if (strpos($key, 'HTTP_') === 0) {
                    $headers[strtr(ucwords(strtolower(strtr(substr($key, 5), '_', ' '))), ' ', '-')] = $value;
                } elseif (strpos($key, 'CONTENT_') === 0) {
                    $name = substr($key, 8); // Remove "Content-"
                    $headers['Content-' . (($name == 'MD5') ? $name : ucfirst(strtolower($name)))] = $value;
                }
            }
        }

        $this->setHeaders($headers);

        // Set method
        if (isset($this->serverParams['REQUEST_METHOD'])) {
            $this->setMethod($this->serverParams['REQUEST_METHOD']);
        }

        // Set HTTP version
        if (isset($this->serverParams['SERVER_PROTOCOL'])) {
            $this->setVersion($this->serverParams['SERVER_PROTOCOL']);
        }

        // URI scheme
        if ((! empty($this->serverParams['HTTPS']) && strtolower($this->serverParams['HTTPS']) !== 'off')
            || (! empty($this->serverParams['HTTP_X_FORWARDED_PROTO'])
                 && $this->serverParams['HTTP_X_FORWARDED_PROTO'] == 'https')
        ) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }
        $this->setScheme($scheme);

        // URI query
        if (isset($this->serverParams['QUERY_STRING'])) {
            $this->setQueryString($this->serverParams['QUERY_STRING']);
        }

        return $this;
    }

    /**
     * getServer.
     *
     * @param string|null $name Nome do parâmetro a ser recuperado ou null para obter o contêiner inteiro.
     * @param mixed|null $default Valor padrão a ser usado quando o parâmetro estiver ausente.
     * @return ParametersInterface|mixed
     */
    public function getServer($name = null, $default = null)
    {
        if ($this->serverParams === null) {
            $this->serverParams = new Parameters();
        }

        if ($name === null) {
            return $this->serverParams;
        }

        return $this->serverParams->get($name, $default);
    }

    /**
     * setHeaders.
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * getHeaders.
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * setMethod.
     *
     * @param string $method
     * @return self
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * getMethod.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Define a versão HTTP - 1.0 ou 1.1
     *
     * @param string $version
     * @return self
     * @throws Exception
     */
    public function setVersion($version)
    {
        if ($version != 'HTTP/1.0' && $version != 'HTTP/1.1') {
            throw new Exception(
                'Versão HTTP inválida ou não suportada: ' . $version
            );
        }
        $this->version = $version;
        return $this;
    }

    /**
     * Retornar a versão HTTP para esta solicitação.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * setScheme.
     *
     * @param string $scheme
     * @return self
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * getScheme.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Set the query string
     *
     * If an array is provided, will encode this array of parameters into a
     * query string. Array values will be represented in the query string using
     * PHP's common square bracket notation.
     *
     * @param  string|array $query
     * @return Uri
     */
    public function setQueryString($query)
    {
        if (is_array($query)) {
            // We replace the + used for spaces by http_build_query with the
            // more standard %20.
            $query = str_replace('+', '%20', http_build_query($query));
        }

        $this->queryString = $query;
        return $this;
    }

}
