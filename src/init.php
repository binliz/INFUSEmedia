<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

spl_autoload_register(function ($classname) {
    $filename = BASE_PATH . '/' . str_replace("\\", '/', $classname) . '.php';
    if (!file_exists($filename)) {
        throw new Exception("when trying to load Class $classname in file $filename - file not found");
    }
    include_once $filename;
});
register_shutdown_function(function () {
    $lastError = error_get_last();
    if (is_array($lastError) && $lastError['type'] == E_ERROR) {
        $backtrace = debug_backtrace();
        $additional = [];
        $additional['hostname'] = gethostname();
        $additional['requestUri'] = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
        $errorMessage = "{$lastError['message']} in file {$lastError['file']} on line {$lastError['line']}";
        file_put_contents(
            'error-log.txt',
            var_export(
                [
                    'level' => 'fatal',
                    'message' => $errorMessage,
                    'trace' => $backtrace,
                    'additional' => $additional
                ],
                1
            )
        );
        echo getFormattedError($lastError['message'], $lastError['file'], $lastError['line'], $backtrace);
    }
});
function getFormattedError($msg, $file, $line, $trace)
{
    $sapiName = php_sapi_name();
    switch ($sapiName) {
        case 'cli':
            return "Error: $msg in $file in line $line\n$trace";
            break;
        case 'fastcgi':
        case 'fcgi':
        case 'fpm-fcgi':
            return "<div style='display:inline-block'>
				<div style=' color: #FF0E00;
    background: #D8D8D8;
    font-size: 1.1em;
    padding: 10px;
    border-radius: 12px 12px 0 0;
    border: 1px solid;'>Error: $msg in $file in line $line</div>
				<div style='line-height: 1.5;
    padding: 10px;
    font-family: sans-serif;
    border: 1px solid;
    color: #FFDEDE;
    background: #564A4A;
    border-radius: 0 0 12px 12px;
	}'>$trace</div>
	</div>";
    }
}

