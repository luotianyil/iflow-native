<?php

namespace iflow\native\Window;

use iflow\native\Config;
use iflow\native\Console\Console;
use iflow\native\Window\NetChannel\WebSocket\Websocket;
use iflow\native\Window\Traits\{ CreateWindowTrait, ServiceBootTrait };

class Window {

    use CreateWindowTrait, ServiceBootTrait;

    protected Console $console;

    protected Websocket $websocket;

    public function __construct(protected array|Config $config = []) {
        if (is_array($this->config)) $this->config = new Config($this->config);
    }


    /**
     * @return Config
     */
    public function getConfig(): Config {
        return $this->config;
    }

}
