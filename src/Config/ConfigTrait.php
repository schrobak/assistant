<?php

namespace Revolve\Assistant\Config;

trait ConfigTrait
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param null|array $config
     */
    public function __construct(array $config = null)
    {
        if ($config !== null) {
            $this->setConfig($config);
        }
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }
}
