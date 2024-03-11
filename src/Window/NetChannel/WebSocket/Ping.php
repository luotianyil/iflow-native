<?php

namespace iflow\native\Window\NetChannel\WebSocket;


use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use Workerman\Connection\TcpConnection;
use Workerman\Timer;

class Ping {


    protected mixed $pingTimeoutTimer = null;

    protected mixed $pingIntervalTimer = null;

    public function __construct(
        protected TcpConnection $connection,
        protected float         $pingTimer,
        protected float         $pingTimeOut
    ) {}

    public function ping(): bool {
        $this->pingIntervalTimer && Timer::del($this->pingIntervalTimer);
        $this->pingIntervalTimer = Timer::add($this->pingTimer, function () {
            $this->connection->send(strval(PackageFormatter::ping()));
            $this->clearPingTimeOut($this->pingTimeOut);
        });
        return true;
    }

    public function clearPingTimeOut($timeout = null) {
        $this->pingTimeoutTimer && Timer::del($this->pingTimeoutTimer);
        $this->pingTimeoutTimer = Timer::add(
            $timeout === null ? $this->pingTimer + $this->pingTimeOut : $timeout
            , fn () => $this->close());
    }

    public function clear(): bool {
        Timer::del($this->pingIntervalTimer);
        Timer::del($this->pingTimeoutTimer);
        return true;
    }

    public function close(): void {
        // 断开服务
        $this->connection->close();
    }


}