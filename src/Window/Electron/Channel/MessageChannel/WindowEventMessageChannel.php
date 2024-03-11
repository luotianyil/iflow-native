<?php

namespace iflow\native\Window\Electron\Channel\MessageChannel;

use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use iflow\native\Window\NetChannel\WebSocket\Websocket;
use Workerman\Connection\TcpConnection;

class WindowEventMessageChannel {

    public function __construct(protected Websocket $websocket, protected TcpConnection $connection, protected PackageFormatter $data) {
    }


    /**
     * @param string $event 事件名称
     * @return bool
     */
    public function message(string $event): bool {

        $messageEvent = $this->websocket -> getNative() -> getConfig() ->getWebsocket()['messageEnum'];

        if (isset($messageEvent) && array_key_exists($event, $messageEvent)) {
            $eventMethod = $messageEvent[$event];

            if (is_callable($eventMethod))
                return $eventMethod($this->websocket, $this->connection, $this->data) ?: true;

            if (class_exists($eventMethod))
                (new $eventMethod) -> handle($this->websocket, $this->connection, $this->data);

            return true;
        }

        return false;
    }

}