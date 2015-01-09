<?php

namespace Revolve\Assistant\Provider\Iron;

use IronMQ as BaseIronMQ;

class IronMQ extends BaseIronMQ
{
    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->getConfigData($config);

        $this->url = "{$this->protocol}://{$this->host}:{$this->port}/{$this->api_version}/";

        return $this;
    }

    /**
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        if ($config !== null) {
            $this->setConfig($config);
        }
    }
}
