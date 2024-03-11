<?php

namespace iflow\native\Window\NetChannel\WebSocket;

use iflow\native\Config;
use iflow\native\Native;
use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use iflow\native\Window\NetChannel\WebSocket\Traits\{
    StartTrait, SenderTraits
};
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

class Websocket {

    use StartTrait, SenderTraits;

    protected Worker $server;

    protected array $workConnectionMap = [];

    public function __construct(protected Config|array $config, protected Native $native) {
        if (!is_array($this->config)) $this->config = $this->config -> getWebsocket();
    }

    /**
     * 消息推送
     * @param mixed $windowFastId
     * @param mixed $data
     * @return bool
     * @throws \Throwable
     */
    public function emit(mixed $windowFastId, mixed $data): bool {

        $fastId = $this->workConnectionMap[
            $windowFastId === 'fastId' ? $this -> getSid() : $windowFastId
        ] ?? 0;

        $data = PackageFormatter::create(
            '4'.PackageFormatter::EVENT . $this->nsp . ',', [ 'data' => $data ]
        ) -> toString();

        return $this->server -> connections[$fastId] -> send($data) ?: false;
    }


    /**
     * 响应请求
     * @param mixed $windowFastId
     * @param string $requestHasUuid 请求识别号
     * @param mixed $data 响应数据
     * @return bool
     * @throws \Throwable
     */
    public function emitResponse(mixed $windowFastId, string $requestHasUuid, mixed $data): bool {
        return $this->emit($windowFastId, [ 'response', [ 'requestHasUuid' => $requestHasUuid, 'data' => $data ] ]);
    }


    /**
     * 向窗口推送消息
     * @param mixed $windowFastId
     * @param mixed $data
     * @return bool
     * @throws \Throwable
     */
    public function emitWindowMessageChannel(mixed $windowFastId, mixed $data): bool {
        return $this -> emit($windowFastId, [ 'windowMessageChannel', $data ]);
    }


    /**
     * 启动服务
     * @return void
     * @throws \Exception
     */
    public function start(): void {
        try {
            $address = "websocket://{$this -> config['host']}:{$this -> config['port']}";
            $this->server = new Worker(
                $address, $this->config['context'] ?? []
            );

            $this->server -> name = uniqid('iflowNative_');

            $this -> server -> onWorkerStart = fn (Worker $worker) => $this -> onStart($worker);

            $this -> server -> onWebSocketConnect = fn (TcpConnection $connection, Request $request)
                => $this -> onWebSocketConnect($connection, $request);

            $this -> server -> onMessage = fn (TcpConnection $connection, mixed $message)
                => $this -> onMessage($connection, $message);

            $this -> server -> onClose = fn () => $this -> onClose();

            $this->server -> listen();
            Worker::runAll();
        } catch (\Throwable $throwable) {
            $this->getNative() -> getConsole() -> writeln($throwable);
        }
    }


    /**
     * @param string $sid
     * @param int $id
     */
    public function setWorkConnectionMap(string $sid, int $id): void {
        $this->workConnectionMap[$sid] = $id;
    }

    /**
     * @return Native
     */
    public function getNative(): Native {
        return $this->native;
    }


    /**
     * 事件回调注册
     * @param string $event
     * @param callable $method
     * @return void
     */
    public function registerMessageEvent(string $event, callable $method): void {
        $this->config -> registerMessageEvent($event, $method);
    }
}