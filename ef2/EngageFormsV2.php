<?php


namespace engagewp\engageforms\ef2;


use engagewp\engageforms\ef2\Fields\FieldTypeFactory;
use engagewp\engageforms\ef2\Transients\Cf1TransientsApi;
use engagewp\engageforms\ef2\Services\ServiceContract;

class EngageFormsV2 extends \engagewp\EngageContainers\Service\Container implements EngageFormsV2Contract
{

    /**
     * Path to main plugin file
     *
     * @since 1.8.0
     *
     * @var string
     */
    protected $coreDirPath;

    /**
     * URL for main plugin file
     *
     * @since 1.8.0
     *
     * @var string
     */
    protected $coreUrl;

    /**
     * EngageFormsV2 constructor.
     *
     * @since 1.8.0
     */
    public function __construct()
    {
        $this->singleton(Hooks::class, function () {
            return new Hooks($this);
        });
        $this->singleton(Cf1TransientsApi::class, function () {
            return new Cf1TransientsApi();
        });
        $this->singleton(FieldTypeFactory::class, function () {
            return new FieldTypeFactory();
        });
    }

    /**
     * Register a service with container
     *
     * @param ServiceContract $service The service to register
     *
     * @param boolean $isSingleton Is service a singleton?
     *
     * @return $this
     * @since 1.8.0
     *
     */
    public function registerService(ServiceContract $service, $isSingleton)
    {
        if (!$service->isSingleton()) {
            $this->bind($service->getIdentifier(), $service->register($this));
        } else {
            $this->singleton($service->getIdentifier(), $service->register($this));
        }
        return $this;
    }


    /**
     * Get service from container
     *
     * @param string $identifier
     *
     * @return mixed
     * @since 1.8.0
     *
     */
    public function getService($identifier)
    {
        return $this->make($identifier);
    }

    /**
     * Set path to main plugin file
     *
     * @param string $coreDirPath
     *
     * @return $this
     * @since 1.8.0
     *
     */
    public function setCoreDir($coreDirPath)
    {
        $this->coreDirPath = $coreDirPath;
        return $this;
    }

    /**
     * Get path to main plugin file
     *
     * @return string
     * @since 1.8.0
     *
     */
    public function getCoreDir()
    {
        if (is_string($this->coreDirPath)) {
            return $this->coreDirPath;
        }
        if (defined('EFCORE_PATH')) {
            return EFCORE_PATH;
        }

        return '';
    }

    /* @inheritdoc */
    public function setCoreUrl($coreUrl)
    {
        $this->coreUrl = $coreUrl;
        return $this;
    }

    /** @inheritdoc */
    public function getCoreUrl()
    {
        if ($this->coreUrl) {
            return $this->coreUrl;
        }

        if (defined('EFCORE_URL')) {
            return EFCORE_URL;
        }

        return '';
    }

    /**
     * Get the singleton hooks instance
     *
     * @return \engagewp\EngageContainers\Interfaces\ProvidesService|Hooks
     * @since 1.8.0
     *
     */
    public function getHooks()
    {
        return $this->make(Hooks::class);
    }

    /**
     * Get our transients API
     *
     * @return \engagewp\EngageContainers\Interfaces\ProvidesService|Cf1TransientsApi
     * @since 1.8.0
     *
     */
    public function getTransientsApi()
    {
        return $this->make(Cf1TransientsApi::class);
    }

    /**
     * Get field type factory
     *
     * @return FieldTypeFactory
     * @since 1.8.0
     *
     */
    public function getFieldTypeFactory()
    {
        return $this->make(FieldTypeFactory::class);
    }

    /** @inheritdoc */
    public function getWpdb()
    {
        global $wpdb;
        return $wpdb;
    }
}
