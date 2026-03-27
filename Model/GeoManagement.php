<?php
/**
 * @description Geo management model
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\GeoIp\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\Http;
use D3p1\GeoIp\Api\GeoMetadataInterface;
use D3p1\GeoIp\Api\GeoManagementInterface;
use D3p1\GeoIp\Api\SystemConfigInterface;
use D3p1\GeoIp\Api\SessionInterface;

class GeoManagement implements GeoManagementInterface
{
    /**
     * @var SessionInterface
     */
    protected $_session;

    /**
     * @var SystemConfigInterface
     */
    protected $_config;

    /**
     * Constructor
     *
     * @param SessionInterface      $session
     * @param SystemConfigInterface $config
     */
    public function __construct(SessionInterface $session, SystemConfigInterface $config)
    {
        $this->_session = $session;
        $this->_config  = $config;
    }

    /**
     * Process request and get store related to its geo information
     *
     * @param  RequestInterface|Http $request
     * @return int|null
     */
    public function processRequest($request)
    {
        if ($country = $request->getHeader(GeoMetadataInterface::GEO_COUNTRY_HEADER)) {
            $storeId = $this->_config->getStoreIdByCountryCode($country);
            $this->_session->setStoreId($storeId);
        }

        return $this->_session->getUserStoreIdFromIp();
    }
}
