<?php
/**
 * @description Logger interface
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\GeoIp\Api;

interface LoggerInterface
{
    /**
     * Add a log record at the INFO level
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return bool
     */
    public function info($message, array $context = array());
}
