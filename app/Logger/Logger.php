<?php

namespace App\Logger;

class Logger
{
    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    private static function log(string $level, string $message, array $context): void
    {
        $log = sprintf("[%s] %s: %s %s\n", date('Y-m-d H:i:s'), $level, $message, json_encode($context));
        file_put_contents(__DIR__ . '/../../storage/logs/app.log', $log, FILE_APPEND);
    }
}