<?php

namespace iflow\native\Window\NetChannel\WebSocket\Enum;

use iflow\native\Window\Electron\Channel\MessageChannel\WindowEventMessageChannel;
use iflow\native\Window\Electron\Events\Enum\WindowEnum;
use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use iflow\native\Window\NetChannel\WebSocket\Websocket;
use Workerman\Connection\TcpConnection;

enum MessageTypeEnum: int {

    case CONNECT = 0;
    
    case EVENT = 2;

    public function onMessage(Websocket $websocket, TcpConnection $connection, PackageFormatter $data): void {
        match ($this) {
            MessageTypeEnum::CONNECT => $this -> onConnect(...func_get_args()),
            MessageTypeEnum::EVENT => $this->onEvent($websocket, $connection, $data),
            default => $connection -> close()
        };
    }

    /**
     * @param Websocket $websocket
     * @param TcpConnection $connection
     * @param PackageFormatter $data
     * @return bool
     * @throws \Throwable
     */
    protected function onConnect(
        Websocket $websocket,
        TcpConnection $connection,
        PackageFormatter $data
    ): bool {
        $packet = PackageFormatter::create(PackageFormatter::CONNECT);
        if ($websocket -> getEIO() >= 4) {
            $packet->data = ['sid' => $websocket -> getSid()];
        }

        $websocket -> setWorkConnectionMap($websocket -> getSid(), $connection -> id);

        return $connection -> send(
            $packet::message($packet -> toString(), nsp: $websocket -> getNsp())
        );
    }

    protected function onEvent(Websocket $websocket, TcpConnection $connection, PackageFormatter $data): bool {
        if ((new WindowEventMessageChannel($websocket, $connection, $data)) -> message($data -> data[0])) return true;

        if ($data -> data[0] === 'check_port_service') {
            return $websocket -> emit($websocket -> getSid(), $data -> data[0]);
        }

        if ($enum = WindowEnum::tryFrom($data ->  data[0])) {
            $enum->onPackage($websocket, $connection, $data);
            return true;
        }

        return $websocket -> emit($websocket -> getSid(), $data -> data);
    }

}
