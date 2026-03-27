<?php
/**
 * @description System config model
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\GeoIp\Model\System;

use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use D3p1\GeoIp\Api\SystemConfigInterface;

class Config implements SystemConfigInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * Get store ID by country code (in IS0 2 format)
     *
     * @param  string $countryCode
     * @return int|null
     */
    public function getStoreIdByCountryCode($countryCode)
    {
        $collection = $this->_collectionFactory->create();

        /**
         * @note Get config data related to this default country
         * @note Because the country code values are saved
         *       using the ISO 2 format, it is possible to filter
         *       using this like condition (all country codes
         *       have 2 characters and are saved using a ',' as separator,
         *       so it is not possible to have more than one coincidence)
         */
        $collection->addFieldToFilter('path', array('eq' => self::GEO_IP_COUNTRIES));
        $collection->addFieldToFilter('value', array('like' => '%' . $countryCode . '%'));

        /**
         * @note Get first item
         * @note We are going to assume that there
         *       is only one store related to this country
         */
        $item = $collection->getFirstItem();

        /**
         * @note Check system config item
         * @note All config values are at store level,
         *       so the scope ID is related to a store ID
         */
        if (($item) && ($storeId = $item->getData('scope_id'))) {
            return $storeId;
        }

        /**
         * @note Return no store ID associated to this country
         */
        return null;
    }
}
