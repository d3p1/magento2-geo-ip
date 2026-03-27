<?php
/**
 * @description Handler for custom logger
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\GeoIp\Logger\Handler;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use D3p1\GeoIp\Logger\Logger;

class Base extends BaseHandler
{
    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * @var string
     */
    protected $fileName = '/var/log/geo.log';
}
