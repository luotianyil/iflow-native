<?php

namespace iflow\native\Window\Electron\Channel\MessageChannel;

use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use iflow\native\Window\NetChannel\WebSocket\Websocket;

class MessageChannel {

    public function __construct(protected Websocket $websocket, protected PackageFormatter $data) {
    }


    /**
     * @return void
     * @throws \Throwable
     */
    public function message(): void {
        $this->websocket -> emit($this->websocket->getSid(), [ 'windowMessage',  $this->data -> data ]);
    }

}