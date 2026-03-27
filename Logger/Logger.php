<?php
/**
 * @description Custom logger
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\GeoIp\Logger;

use Magento\Framework\Logger\Monolog;
use Bina\GeoIp\Api\LoggerInterface;

class Logger extends Monolog implements LoggerInterface
{}
