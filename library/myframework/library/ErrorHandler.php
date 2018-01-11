<?php

namespace sys\core;

class ErrorHandler {

    private $error;

    public function __construct() {
        set_error_handler(array($this, 'systemErrorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        register_shutdown_function(array($this, 'shutdownHandler'));
    }

    /**
     * systemErrorHandler
     *
     * Método que será usado no tratamento de erros.
     * Se ocorrer um erro fatal sai do sistema.
     * Caso o erro seja do tipo WARNING, NOTICE ou desconhecido retorna o valor booleano true.
     * Em ambos os casos aloca a mensagem de erro em $_SESSION['session_error'] e grava um log no banco de dados.
     *
     * @param int $errno - Número do erro
     * @param str $errstr - Descrição do erro
     * @param str $errfile - Arquivo em que ocorreu o erro
     * @param str $errline - Linha no arquivo em que ocorreu o erro
     * @return boolean
     */
    public function systemErrorHandler($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_USER_ERROR:
                $this->error = "<b>My ERROR</b> [$errno] $errstr\n Fatal error on line $errline in file $errfile\n Aborting...\n";
                break;
            case E_USER_WARNING:
                $this->error = "<b>My WARNING</b> [$errno] $errstr on $errfile line $errline\n";
                break;
            case E_USER_NOTICE:
                $this->error = "<b>My NOTICE</b> [$errno] $errstr on $errfile line $errline\n";
                break;
            default:
                $this->error = "Unknown error type: [$errno] $errstr on $errfile line $errline\n";
                break;
        }
        $this->setSessionError($this->error);
        $this->setLogError($this->error);
        if ($errno == E_USER_ERROR) {
            exit(1);
        }
        return true;
    }

    /**
     * setSessionError
     * Aloca a mensagem de erro num novo índice do array $_SESSION['session_error']
     *
     * @param str $error - Mensagem de erro
     */
    private function setSessionError($error) {
        if (!isset($_SESSION['session_error']) || !is_array($_SESSION['session_error'])) {
            $_SESSION['session_error'] = array();
        }
        $_SESSION['session_error'][] = $error;
    }

    /**
     * setLogError
     *
     * Grava um novo registro de erro no log do sistema
     *
     * @global \KdDoctor\classes\User $objUser - Objeto usuário
     * @global \KdDoctor\classes\Log $objLog - Objeto log
     * @param str $error - Mensagem de erro
     */
    private function setLogError($error) {
        global $objUser;
        global $objLog;

        if (isset($objUser) && $objUser != null && isset($objLog) && $objLog != null) {
            $user_id = $objUser->checkAuth() ? $objUser->getUserData('id') : 0;
            $objLog->setLog('erro', $error, 0, $user_id, USER_IP);
        }
    }

    /**
     * Error Handler
     *
     * This is the custom error handler that is declared at the (relative)
     * top of CodeIgniter.php. The main reason we use this is to permit
     * PHP errors to be logged in our own log files since the user may
     * not have access to server logs. Since this function effectively
     * intercepts PHP errors, however, we also need to display errors
     * based on the current error_reporting level.
     * We do that with the use of a PHP error template.
     *
     * @param	int	$severity
     * @param	string	$message
     * @param	string	$filepath
     * @param	int	$line
     * @return	void
     */
    function errorHandler($severity, $message, $filepath, $line)
    {
        $is_error = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);

        // When an error occurred, set the status header to '500 Internal Server Error'
        // to indicate to the client something went wrong.
        // This can't be done within the $_error->show_php_error method because
        // it is only called when the display_errors flag is set (which isn't usually
        // the case in a production environment) or when errors are ignored because
        // they are above the error_reporting threshold.
        if ($is_error) {
            set_status_header(500);
        }

        // Should we ignore the error? We'll get the current error_reporting
        // level and add its bits with the severity bits to find out.
        if (($severity & error_reporting()) !== $severity) {
            return;
        }

        $_error = & load_class('Exceptions', 'core');
        $_error->log_exception($severity, $message, $filepath, $line);

        // Should we display the error?
        if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors'))) {
            $_error->show_php_error($severity, $message, $filepath, $line);
        }

        // If the error is fatal, the execution of the script should be stopped because
        // errors can't be recovered from. Halting the script conforms with PHP's
        // default error handling. See http://www.php.net/manual/en/errorfunc.constants.php
        if ($is_error) {
            exit(1); // EXIT_ERROR
        }
    }

    /**
     * Exception Handler
     *
     * Sends uncaught exceptions to the logger and displays them
     * only if display_errors is On so that they don't show up in
     * production environments.
     *
     * @param	Exception	$exception
     * @return	void
     */
    function exceptionHandler($exception) {
        $_error = & load_class('Exceptions', 'core');
        $_error->log_exception('error', 'Exception: ' . $exception->getMessage(), $exception->getFile(), $exception->getLine());

        is_cli() OR set_status_header(500);
        // Should we display the error?
        if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors'))) {
            $_error->show_exception($exception);
        }

        exit(1); // EXIT_ERROR
    }

    /**
     * Shutdown Handler
     *
     * This is the shutdown handler that is declared at the top
     * of CodeIgniter.php. The main reason we use this is to simulate
     * a complete custom exception handler.
     *
     * E_STRICT is purposively neglected because such events may have
     * been caught. Duplication or none? None is preferred for now.
     *
     * @link	http://insomanic.me.uk/post/229851073/php-trick-catching-fatal-errors-e-error-with-a
     * @return	void
     */
    function shutdownHandler()
    {
        $last_error = error_get_last();
        if (isset($last_error) &&
                ($last_error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING))) {
            _error_handler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }
}
