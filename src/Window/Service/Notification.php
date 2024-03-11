<?php

namespace iflow\native\Window\Service;

use iflow\native\Native;
use iflow\native\Window\Interfaces\RegisterServiceInterface;
use iflow\native\Window\Service\Options\NotificationOptions;

class Notification implements RegisterServiceInterface {

    protected Native $native;

    /**
     * 窗口通知
     * @param string|NotificationOptions $title 通知标题，在通知窗口顶部显示
     * @param string $subtitle 通知的副标题, 显示在标题下面
     * @param string $body 通知的正文文本，将显示在标题或副标题下面
     * @param array $options
     * @return bool
     * @throws \Throwable
     */
    public function notification(string|NotificationOptions $title, string $subtitle, string $body, array $options = []): bool {

        if ($title instanceof NotificationOptions) {
            $options = $title -> toArray($options);
        } else {
            $options['title'] = $title;
            $options['subtitle'] = $subtitle;
            $options['body'] = $body;
        }

        return $this->native -> getWebsocket() -> emitWindowMessageChannel(
            $this->native->getWebsocket()->getSid(),
            [ 'event' => 'notification', 'options' => $options, 'windowId' => $options['windowId'] ?? 0 ]
        );
    }

    public function boot(Native $native): Notification {

        // TODO: Implement boot() method.
        $this->native = $native;
        return $this;
    }
}