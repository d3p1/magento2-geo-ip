<?php
/**
 * @description Geo IP info interface
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\GeoIp\Api;

interface IpInfoInterface
{
    /**
     * Get IP details
     *
     * @param  string|null $ip
     * @return mixed
     */
    public function getDetails($ip = null);
}