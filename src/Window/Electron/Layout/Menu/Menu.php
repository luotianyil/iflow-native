<?php

namespace iflow\native\Window\Electron\Layout\Menu;

use iflow\native\Native;
use iflow\native\Window\Electron\Layout\Menu\Options\MenuOptions;
use iflow\native\Window\Interfaces\RegisterServiceInterface;

class Menu implements RegisterServiceInterface {

    protected Native $native;

    /**
     * 向主窗口更新菜单
     * @param MenuOptions $menu
     * @param array $data
     * @param bool $menuBarVisible
     * @return bool
     * @throws \Throwable
     */
    public function menu(MenuOptions $menu, array $data, bool $menuBarVisible = true): bool {
        return $this->native -> getWebsocket() -> emitWindowMessageChannel(
            $this->native -> getWebsocket() -> getSid(),
            [
                'event' => 'menu',
                'options' => [
                    'menu' => $menu -> getMenuItemsToArray(),
                    'menuBarVisible' => $menuBarVisible,
                    'windowId' => $data['windowId'] ?? 0
                ]
            ]
        );
    }

    public function boot(Native $native): RegisterServiceInterface {
        // TODO: Implement boot() method.
        $this->native = $native;
        return $this;
    }
}
