<?php

namespace iflow\native\Window\NetChannel\WebSocket\Traits;

use iflow\native\Window\WindowConfig;

trait SenderTraits {

    /**
     * 创建窗口
     * @param WindowConfig $windowConfig
     * @return bool
     * @throws \Throwable
     */
    public function createWindow(WindowConfig $windowConfig): bool {
        return $this->emit($this->getSid(), $windowConfig->toArray());
    }

    /**
     * 向指定窗口发送信息
     * @param array $data
     * @param string $windowUuid
     * @return bool
     * @throws \Throwable
     */
    public function senderWindowMessage(array $data, string $windowUuid = ''): bool {
        return $this -> emit(
            $this -> getSid(),
            [ 'windowMessage', [ 'windowUuid' => $windowUuid, 'data' => $data ] ]
        );
    }

}
