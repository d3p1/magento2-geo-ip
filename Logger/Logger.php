<?php
/**
 * @description Custom logger
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\GeoIp\Logger;

use Magento\Framework\Logger\Monolog;
use D3p1\GeoIp\Api\LoggerInterface;

class Logger extends Monolog implements LoggerInterface
{}
