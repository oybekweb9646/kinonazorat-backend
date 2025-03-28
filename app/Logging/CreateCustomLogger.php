<?php

namespace App\Logging;

use Monolog\Logger;

class CreateCustomLogger
{
    /**
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('custom_create_log');
        $logger->pushHandler(app(DatabaseLoggerHandler::class));
        return $logger;
    }
}
