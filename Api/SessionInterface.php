<?php
/**
 * @description Geo IP session interface
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\GeoIp\Api;

interface SessionInterface
{
    /**
     * @const STORE_ID
     */
    const STORE_ID = 'store_id';

    /**
     * Get user store ID from IP
     *
     * @return int
     * @note   It returns 0 as store ID when it is not possible
     *         to determine a store for user IP
     */
    public function getUserStoreIdFromIp();

    /**
     * Get store ID
     *
     * @return int|null
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param  int $storeId
     * @return void
     */
    public function setStoreId($storeId);
}
