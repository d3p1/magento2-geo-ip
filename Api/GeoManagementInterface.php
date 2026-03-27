<?php
/**
 * @description Geo management interface
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\GeoIp\Api;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\Http;

interface GeoManagementInterface
{
    /**
     * Process request and get store related to its geo information
     *
     * @param  RequestInterface|Http $request
     * @return int|null
     */
    public function processRequest($request);
}
