<?php

namespace iflow\native\Window\NetChannel\Interfaces;

interface ReadyCommunicationInterface {

    /**
     * 发送消息
     * @param string $windowUuid
     * @param mixed $data
     * @return bool
     */
    public function emit(string $windowUuid, mixed $data): bool;

    /**
     * 接收消息
     * @param string $windowUuid
     * @param array $data
     * @return bool
     */
    public function onPacket(string $windowUuid, array $data): bool;

    /**
     * 设置websocket原始对象
     * @param object $websocket
     * @return ReadyCommunicationInterface
     */
    public function setWebsocketServer(mixed $websocket): ReadyCommunicationInterface;

}