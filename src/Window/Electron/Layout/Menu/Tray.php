<?php

namespace iflow\native\Window\Electron\Layout\Menu;

use iflow\native\Native;
use iflow\native\Window\Electron\Layout\Menu\Options\TrayOptions;
use iflow\native\Window\Interfaces\RegisterServiceInterface;

class Tray implements RegisterServiceInterface {

    protected Native $native;

    /**
     * @param TrayOptions $trayOptions
     * @param array $data
     * @return bool
     * @throws \Throwable
     */
    public function tray(TrayOptions $trayOptions, array $data): bool {
        return $this->native -> getWebsocket() -> emitWindowMessageChannel(
            $this->native -> getWebsocket() -> getSid(),
            [
                'event' => 'tray',
                'options' => [
                    'tray' => $trayOptions -> toArray()
                ],
                'windowId' => $data['windowId'] ?? 0
            ]
        );
    }

    public function boot(Native $native): RegisterServiceInterface {
        // TODO: Implement boot() method.
        $this->native = $native;
        return $this;
    }
}
