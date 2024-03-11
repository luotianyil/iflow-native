<?php

namespace iflow\native\Window\NetChannel;

use iflow\native\Window\NetChannel\Interfaces\ReadyCommunicationInterface;
use iflow\native\Window\NetChannel\WebSocket\Websocket;

class ReadyCommunication implements ReadyCommunicationInterface {

    protected Websocket $websocket;

    /**
     * 发送消息
     * @param string $windowUuid
     * @param mixed $data
     * @return bool
     */
    public function emit(string $windowUuid, mixed $data): bool {
        return true;
    }


    /**
     * 接收消息
     * @param string $windowUuid
     * @param array $data
     * @return bool
     */
    public function onPacket(string $windowUuid, array $data): bool {
        return true;
    }

    public function setWebsocketServer(mixed $websocket): ReadyCommunicationInterface {
        // TODO: Implement setWebsocketServer() method.
        $this->websocket = $websocket;
        return $this;
    }
}
