<?php

namespace Revolve\Assistant\Config;

interface ConfigInterface
{
    /**
     * @return array
     */
    public function getConfig();

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config);
}