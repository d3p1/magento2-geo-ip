<?php
/**
 * @description Geo IP session
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 * @note        This session model is intended to work
 *              like the checkout session model and its get quote feature
 */
namespace D3p1\GeoIp\Model;

use Exception;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\ValidatorInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\SessionStartChecker;
use D3p1\GeoIp\Api\LoggerInterface;
use D3p1\GeoIp\Api\SystemConfigInterface;
use D3p1\GeoIp\Api\SessionInterface;
use D3p1\GeoIp\Api\IpInfoInterfaceFactory;
use D3p1\GeoIp\Api\IpInfoInterface;

class Session extends SessionManager implements SessionInterface
{
    /**
     * @var IpInfoInterfaceFactory
     */
    protected $_ipInfoFactory;

    /**
     * @var SystemConfigInterface
     */
    protected $_config;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var RemoteAddress
     */
    protected $_remoteAddress;

    /**
     * @var IpInfoInterface|null
     */
    private $_ipInfo = null;

    /**
     * Constructor
     *
     * @param IpInfoInterfaceFactory   $ipInfoFactory
     * @param SystemConfigInterface    $config
     * @param LoggerInterface          $logger
     * @param RemoteAddress            $remoteAddress
     * @param Http                     $request
     * @param SidResolverInterface     $sidResolver
     * @param ConfigInterface          $sessionConfig
     * @param SaveHandlerInterface     $saveHandler
     * @param ValidatorInterface       $validator
     * @param StorageInterface         $storage
     * @param CookieManagerInterface   $cookieManager
     * @param CookieMetadataFactory    $cookieMetadataFactory
     * @param State                    $appState
     * @param SessionStartChecker|null $sessionStartChecker
     */
    public function __construct(
        IpInfoInterfaceFactory $ipInfoFactory,
        SystemConfigInterface  $config,
        LoggerInterface        $logger,
        RemoteAddress          $remoteAddress,
        Http                   $request,
        SidResolverInterface   $sidResolver,
        ConfigInterface        $sessionConfig,
        SaveHandlerInterface   $saveHandler,
        ValidatorInterface     $validator,
        StorageInterface       $storage,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory  $cookieMetadataFactory,
        State                  $appState,
        SessionStartChecker    $sessionStartChecker = null
    ) {
        $this->_ipInfoFactory = $ipInfoFactory;
        $this->_config        = $config;
        $this->_logger        = $logger;
        $this->_remoteAddress = $remoteAddress;

        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState,
            $sessionStartChecker
        );
    }

    /**
     * Get user store ID from IP
     *
     * @return int
     * @note   It returns 0 as store ID when it is not possible
     *         to determine a store for user IP
     */
    public function getUserStoreIdFromIp()
    {
        /**
         * @note Check if store ID was already determined
         */
        if (!is_null($this->getStoreId())) {
            /**
             * @note Return store ID
             */
            return $this->getStoreId();
        }

        $storeId = 0;
        try {
            $ipDetails = $this->_getIpDetails();

            /**
             * @note Check if country exists for user IP
             */
            if (isset($ipDetails->country)) {
                /**
                 * @note Get country related to IP
                 */
                $country = $ipDetails->country;

                /**
                 * @note Check if there is a related store ID
                 *       to the country obtained from the user's IP
                 */
                if ($scopeId = $this->_getStoreIdByCountryCode($country)) {
                    $storeId = $scopeId;
                }
            }
        }
        catch (Exception $e) {
            $this->_logger->info(
                '[' . $this->_getUserIp() . ']' . ' ' . $e->getMessage()
            );
        }

        /**
         * @note Set store ID in session to avoid determine it again
         */
        $this->setStoreId($storeId);

        /**
         * @note Return store ID
         */
        return $storeId;
    }

    /**
     * Get store ID
     *
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->storage->getData(self::STORE_ID);
    }

    /**
     * Set store ID
     *
     * @param  int $storeId
     * @return void
     */
    public function setStoreId($storeId)
    {
        $this->storage->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get IP details
     *
     * @return mixed
     */
    protected function _getIpDetails()
    {
        return $this->_getIpInfo()->getDetails($this->_getUserIp());
    }

    /**
     * Get IP info
     *
     * @return IpInfoInterface
     */
    protected function _getIpInfo()
    {
        if (is_null($this->_ipInfo)) {
            $this->_ipInfo = $this->_ipInfoFactory->create();
        }

        return $this->_ipInfo;
    }

    /**
     * Get store ID by country code
     *
     * @param  string $countryCode
     * @return int|null
     */
    private function _getStoreIdByCountryCode($countryCode)
    {
        return $this->_config->getStoreIdByCountryCode($countryCode);
    }

    /**
     * Get user IP
     *
     * @return string
     */
    private function _getUserIp()
    {
        return $this->_remoteAddress->getRemoteAddress();
    }
}
