<?php

namespace LogBug\Service;

use LogBug\Service\Email as EmailErro;

class Error {

    protected $mail;

    public function __construct(EmailErro $mail) {
        $this->mail = $mail;

        register_shutdown_function(array($this, 'shut'));
        set_error_handler(array($this, 'handler'));
    }

//catch function
    function shut() {
        $error = error_get_last();
        if ($error) {
            $this->handler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    function handler($errno, $errstr, $errfile, $errline) {
        switch ($errno) {

            case E_ERROR: // 1 //
                $typestr = 'E_ERROR';
                break;
            case E_WARNING: // 2 //
                $typestr = 'E_WARNING';
                break;
            case E_PARSE: // 4 //
                $typestr = 'E_PARSE';
                break;
            case E_NOTICE: // 8 //
                $typestr = 'E_NOTICE';
                break;
            case E_CORE_ERROR: // 16 //
                $typestr = 'E_CORE_ERROR';
                break;
            case E_CORE_WARNING: // 32 //
                $typestr = 'E_CORE_WARNING';
                break;
            case E_COMPILE_ERROR: // 64 //
                $typestr = 'E_COMPILE_ERROR';
                break;
            case E_CORE_WARNING: // 128 //
                $typestr = 'E_COMPILE_WARNING';
                break;
            case E_USER_ERROR: // 256 //
                $typestr = 'E_USER_ERROR';
                break;
            case E_USER_WARNING: // 512 //
                $typestr = 'E_USER_WARNING';
                break;
            case E_USER_NOTICE: // 1024 //
                $typestr = 'E_USER_NOTICE';
                break;
            case E_STRICT: // 2048 //
                $typestr = 'E_STRICT';
                break;
            case E_RECOVERABLE_ERROR: // 4096 //
                $typestr = 'E_RECOVERABLE_ERROR';
                break;
            case E_DEPRECATED: // 8192 //
                $typestr = 'E_DEPRECATED';
                break;
            case E_USER_DEPRECATED: // 16384 //
                $typestr = 'E_USER_DEPRECATED';
                break;
        }

        $currentDate = new \DateTime('now');
        $message = " Error PHP \n"
                . "file => " . $errfile . " at line : " . $errline . "\n"
                . "type error => " . $typestr . " : " . $errstr . "\n "
                . "Host => " . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "\n"
                . "IP Request => " . $_SERVER['REMOTE_ADDR'] . "\n"
                . "Date => " . $currentDate->format('Y-m-d H:i');

//mail 
        $mail = $this->mail->send($message,$typestr);
    }

}
