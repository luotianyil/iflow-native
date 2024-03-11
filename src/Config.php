<?php

namespace iflow\native;

use iflow\native\Window\Electron\Layout\Menu\Menu;
use iflow\native\Window\Electron\Layout\Menu\Tray;
use iflow\native\Window\RenderTemplate\Template;
use iflow\native\Window\Service\Dialog;
use iflow\native\Window\Service\Notification;

class Config {

    public array $defaultConfig = [
        // 前端资源路径
        'resource' => '',
        // electron_app 可执行文件地址
        'electron_path' => '',
        // electron_app 入口文件
        'electron_entry' => '',
        // 渲染模板参数回调
        'renderTemplateHandle' => '',
        'defaultTemplateConfig' => [],
        // websocket配置
        'websocket' => [
            'type' => 'websocket',
            'host' => '127.0.0.1',
            'isSsl' => false,
            'port' => 8090,
            'context' => [],
            // 消息回调
            'messageEnum' => []
        ],
        // electron 基础配置
        'electron' => [
            'package_tool' => 'pnpm',
            'registry' => 'https://registry.npmmirror.com'
        ],
        'registerService' => [
            Menu::class,
            Tray::class,
            Dialog::class,
            Notification::class
        ],
        // 是否关闭所有窗口后关闭PHP服务
        'closeAllCloseProcessService' => false,
        'RUN_ING_TYPE' => 'development',
        // 构建配置
        'builder' => [
            'electron' => [],
            'phar' => [
                // 构建生成文件目录
                'out-path' => './build',
                'phar-path' => '',
                'root' => '',
                'entry' => '',
                'web-entry' => '',
                'privatekey' => '',
                'micro-path' => ''
            ]
        ]
    ];

    private string $base_path = __DIR__;

    public function __construct(protected array $config) {
        $this->config = array_replace_recursive($this->defaultConfig, $this->config);
    }

    /**
     * @return array
     */
    public function getConfig(): array {
        return $this->config;
    }

    /**
     * 获取前端资源地址
     * @return string
     */
    public function getResource(): string {
        return $this->config['resource'];
    }

    /**
     * 获取 electron_app 可执行文件地址
     * @return string
     */
    public function getElectronRunPath(): string {
        return $this->config['electron_path'];
    }

    /**
     * 获取 electron_app 可执行文件地址
     * @return string
     */
    public function getElectronRunEntry(): string {
        return $this->config['electron_entry'];
    }

    /**
     * 获取模板渲染
     * @return string
     */
    public function getRenderTemplateHandle(): string {
        return $this->config['renderTemplateHandle'] ?: Template::class;
    }

    public function getDefaultTemplateConfig(): array {
        return $this->config['defaultTemplateConfig'] ?: [
            // 是否开启缓存
            'cache_enable' => false,
            // 缓存地址
            'store_path' => getcwd() . DIRECTORY_SEPARATOR . 'template',
            'view_root_path' => getcwd() . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR,
            'view_suffix' => 'html',
            // 渲染方法
            'rendering' => 'template',
            'tags' => []
        ];
    }

    /**
     * @return array
     */
    public function getWebsocket(): array {
        return $this->config['websocket'];
    }

    /**
     * @return array
     */
    public function getElectron(): array {
        return $this->config['electron'];
    }

    /**
     * @return string
     */
    public function getBasePath(): string {
        return $this->getResource() ?: $this->base_path;
    }

    /**
     * 获取 Electron 运行地址
     * @return string
     */
    public function getElectronPath(): string {

        if ($this->getResource()) return $this->getResource();

        $path = str_replace('\\', '/', "{$this -> getBasePath()}/Electron");

        if (is_dir($path)) return $path;

        return str_replace('\\', '/', $this -> getBasePath());
    }

    /**
     * 所有窗口退出后是否关闭服务
     * @return bool
     */
    public function getCloseAllCloseProcessService(): bool {
        return $this->config['closeAllCloseProcessService'] ?? false;
    }

    /**
     * @param string $event
     * @param callable $method
     * @return void
     */
    public function registerMessageEvent(string $event, callable $method): void {
        $this->config['websocket']['messageEnum'][$event] = $method;
    }

    public function registerService(): array {
        return $this->config['registerService'] ?? [];
    }

    public function getBuilder(string $name = ''): array {
        if ($name) return $this->config['builder'][$name] ?? [];
        return $this->config['builder'];
    }

    public function getRunType(): string {
        return $this->config['RUN_ING_TYPE'] ?? 'development';
    }


    public function toArray(): array {
        return $this->config;
    }
}
