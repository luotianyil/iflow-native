<?php

namespace iflow\native\Window;

use iflow\Helper\Str\Str;
use iflow\native\Window\Electron\Layout\Menu\Options\MenuOptions;
use iflow\native\Window\Electron\Layout\Menu\Options\TrayOptions;

class WindowConfig {

    /**
     * 窗口标题
     * @var string
     */
    protected string $title = 'window';

    /**
     * 是否有边框
     * @var bool|string
     */
    protected bool $frame = true;

    /**
     * 窗口是否不可见
     * @var bool
     */
    protected bool $hidden = false;

    /**
     * 标题栏样式
     * @var string
     */
    protected string $titleBarStyle = 'default';

    /**
     * 窗口图标
     * @var string
     */
    protected string $icon;

    /**
     * 是否显示
     * @var bool
     */
    protected bool $show;

    /**
     * 窗口X轴
     * @var float
     */
    protected float $x;

    /**
     * 窗口Y轴
     * @var float
     */
    protected float $y;

    protected float $width;

    protected float $height;

    protected float $minWidth;

    protected float $minHeight;

    protected float $maxWidth;

    protected float $maxHeight;

    protected bool $resizable;

    /**
     * 是否可拖拽
     * @var bool
     */
    protected bool $movable;

    /**
     * 最小化
     * @var bool
     */
    protected bool $minimizable;

    /**
     * 全屏化
     * @var bool
     */
    protected bool $maximizable;

    /**
     * 是否显示窗口菜单
     * @var bool
     */
    protected bool $menuBarVisible;

    /**
     * 窗口是否显示阴影
     * @var bool
     */
    protected bool $shadow;

    /**
     * 是否打开控制台
     * @var bool
     */
    protected bool $openDevTools;

    /**
     * 预加载js
     * @var string
     */
    protected string $preload;

    /**
     * 是否可关闭
     * @var bool
     */
    protected bool $closable;

    protected array $events;

    protected MenuOptions $menu;

    protected TrayOptions $tray;

    /**
     * 快捷键
     * @var array [ [ 'shortcut' => 'Ctrl+Q', 'event' => 'quit', ...options ] ]
     */
    protected array $shortcut;

    protected string $windowUuid = '';

    public function __construct(array $options = []) {
        foreach ($options as $optionKey => $optionValue) {
            if (!property_exists($this, $optionKey))  continue;
            $this -> {$optionKey} = $optionValue;
        }
    }

    /**
     * 设置配置
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setOption(string $key, mixed $value): WindowConfig {
        if (property_exists($this, $key)) $this -> {$key} = $value;
        return $this;
    }

    /**
     * @param array $options 其他扩展参数
     * @return array
     */
    public function toArray(array $options = []): array {

        if (!$this->windowUuid) {
            $this->windowUuid = Str::genUuid();
        }

        $value = get_object_vars($this);
        $value['menu'] = array_key_exists('menu', $value) ? $value['menu'] -> getMenuItemsToArray() : [];
        $value['tray'] = array_key_exists('tray', $value) ? $value['tray'] -> toArray() : [];

        return array_merge($value, $options);
    }

}
