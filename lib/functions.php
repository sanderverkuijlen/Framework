<?php

    function printR($in){
        return '<pre>'.print_r($in, true).'</pre>';
    }

    function exceptionHandler(Exception $e){
        switch(get_class($e)){

            case 'SqlException':
                /* @var $e SqlException */
                echo '<h1>'.get_class($e).'</h1>'.$e->getMessage().'<br>'.printR($e->getQuery()).'<h2>Trace:</h2>'.printR($e->getTrace());
                break;

            case 'ValidationException':
                /* @var $e ValidationException */
                echo '<h1>'.get_class($e).'</h1>'.$e->getMessage().'<br><br><ul><li>'.implode('</li><li>', $e->getFieldMessages()).'</li></ul>';
                break;

            default:
            case 'ClassNotFoundException':
            case 'NotImplementedException':
                echo '<h1>'.get_class($e).'</h1>'.$e->getMessage().'<h2>Trace:</h2>'.printR($e->getTrace());
                break;
        }

    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool
     * @throws Exception
     */
    function errorHandler($errno, $errstr, $errfile, $errline) {

        switch($errno){
            default:
                return true;
                break;

            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                throw new Exception($errstr.'<br><br>'.$errfile.'<br>'.$errline, $errno);
                break;
        }
    }

?>