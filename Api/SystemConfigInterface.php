<?php
/**
 * @description System config interface
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\GeoIp\Api;

interface SystemConfigInterface
{
    /**
     * @const GEO_IP_COUNTRIES
     */
    const GEO_IP_COUNTRIES = 'general/country/geo_ip';

    /**
     * Get store ID by country code (in IS0 2 format)
     *
     * @param  string $countryCode
     * @return int|null
     */
    public function getStoreIdByCountryCode($countryCode);
}
