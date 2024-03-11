<?php

namespace iflow\native;

use iflow\native\Builder\Builder;
use iflow\native\Console\Console;
use iflow\native\Window\NetChannel\WebSocket\Websocket;
use iflow\native\Window\Window;
use iflow\native\Window\WindowConfig;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class Native extends Window {

    /**
     * @description  工作环境 development | production
     */
    protected string $RUN_ING_TYPE = 'production';

    // 主窗口配置
    protected array $mainWindow = [
        'width' => 800,
        'height' => 600
    ];

    public function __construct(protected array|Config $config = []) {
        parent::__construct($this->config);

        $this->console = new Console();
        $this->RUN_ING_TYPE = $this->config -> getRunType();
    }

    /**
     * 应用启动入口
     * @param array $mainWindow 主窗口配置
     * @return void
     * @throws \Exception
     */
    public function start(array $mainWindow = []): void {

        $mainWindow = $mainWindow ?: $this -> mainWindow;
        $this->mainWindow = (new WindowConfig()) -> toArray($mainWindow);

        $this->websocket = new Websocket($this->config, $this);
        $this->boot();
        $this->websocket -> start();
    }

    /**
     * 启动窗口主程序
     * @param string $netUrl
     * @return bool
     */
    public function startElectron(string $netUrl): bool {
        $command = [];

        if ($this -> RUN_ING_TYPE === 'development') {
            if (!file_exists($this->config -> getElectronPath() . DIRECTORY_SEPARATOR . 'install.lock')) {
                $command[] = [
                    $this->config->getElectron()['package_tool'],
                    'install', '--registry', $this->config -> getElectron()['registry'],
                    'callback' => fn () => file_put_contents(
                        $this->config -> getElectronPath() . DIRECTORY_SEPARATOR . 'install.lock',
                        'installed'
                    )
                ];
            }

            $command[] = [
                $this->config -> getElectron()['package_tool'],
                'electron',
                "net-url=$netUrl"
            ];
        } else {
            $command[] = [
                '.'.DIRECTORY_SEPARATOR.$this -> config -> getElectronRunEntry(),
                'electron',
                "net-url=$netUrl"
            ];
        }

        $this->console -> exec($command, cwd: $this->config -> getElectronPath());
        return true;
    }

    public function builder(): bool {
        return (new Builder()) -> builder($this->config, $this->console);
    }

    /**
     * @return Console
     */
    public function getConsole(): Console {
        return $this->console;
    }

    /**
     * @return array
     */
    public function getMainWindow(): array {
        return $this->mainWindow;
    }

    /**
     * @return $this
     */
    public function getNative(): Native {
        return $this;
    }

    /**
     * @return Websocket
     */
    public function getWebsocket(): Websocket {
        return $this->websocket;
    }
}
