<?php

namespace iflow\native\Window\NetChannel\WebSocket\Traits;

use iflow\native\Window\NetChannel\WebSocket\Enum\MessageTypeEnum;
use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use iflow\native\Window\NetChannel\WebSocket\Ping;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

trait StartTrait {

    protected string $sid;

    protected string $nsp = '/';

    protected int|string $EIO;

    protected Ping $ping;

    public function onStart(Worker $worker): void {

        $host = $this -> config['isSsl'] ? 'https' : 'http';

        $this -> native -> startElectron(
            "$host://{$this -> config['host']}:{$this -> config['port']}/socket.io"
        );
    }

    /**
     * 初始化连接
     * @param TcpConnection $connection
     * @return void
     * @throws \Throwable
     */
    public function onConnect(TcpConnection $connection): void {
    }

    /**
     * 初始化连接
     * @param TcpConnection $connection
     * @param Request $request
     * @return void
     * @throws \Throwable
     */
    public function onWebSocketConnect(TcpConnection $connection, Request $request): void {

        $this->sid = base64_encode(uniqid());

        $this->EIO = $request -> get('EIO', '');
        $this->nsp = rtrim($request -> path(), '/');

        $this->ping = new Ping($connection, 25, 60);

        $payload = json_encode(
            [
                'sid'          => $this->sid,
                'upgrades'     => [],
                'pingInterval' => 25000,
                'pingTimeout'  => 60000
            ]
        );

        $connection -> send(PackageFormatter::OPEN. $payload);

        if ($this->EIO < 4) {
            $this->ping -> clearPingTimeOut();
        }

        if ($this->EIO >= 4) {
            $packet = PackageFormatter::create(PackageFormatter::CONNECT);
            $packet->data = ['sid' => $this->sid];
            $connection -> send(
                PackageFormatter::message($packet -> toString(), nsp: $this->nsp)
            );
            $this->ping -> ping();
        }
    }

    public function onMessage(TcpConnection $connection, mixed $message): void {
        $data = PackageFormatter::fromString($message);
        $packData = PackageFormatter::decode($data -> data);

        match (intval($data -> type)) {
            PackageFormatter::MESSAGE => MessageTypeEnum::from(intval($packData -> type))
                -> onMessage($this, $connection, $packData),
            PackageFormatter::PING => $connection -> send(PackageFormatter::PONG),
            PackageFormatter::PONG => $this->ping -> ping(),
            default => $connection -> close()
        };
    }

    public function onClose(): void {
        unset($this -> workConnectionMap[$this->sid]);
    }

    /**
     * @return int|string
     */
    public function getEIO(): int|string {
        return $this->EIO;
    }

    /**
     * @return string
     */
    public function getSid(): string {
        return $this->sid;
    }

    /**
     * @return string
     */
    public function getNsp(): string {
        return $this->nsp;
    }
}