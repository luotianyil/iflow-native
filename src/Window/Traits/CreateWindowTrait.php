<?php

namespace iflow\native\Window\Traits;

use iflow\Helper\Arr\Arr;
use iflow\native\Window\WindowConfig;

trait CreateWindowTrait {

    /**
     * 窗口列表
     * @var array<Arr>
     */
    protected array $windowUuidMap = [];

    /**
     * 新建窗口
     * @param WindowConfig $browserWindowConfig
     * @return bool
     * @throws \Throwable
     */
    public function createWindow(WindowConfig $browserWindowConfig): bool {
        return $this -> websocket -> emit(
            $this -> websocket -> getSid(),
            [ 'createWindow', $browserWindowConfig -> toArray() ]
        );
    }

    /**
     * 关闭指定窗口
     * @param string $windowUuid
     * @return bool
     * @throws \Throwable
     */
    public function closeWindow(string $windowUuid): bool {
        return $this -> websocket -> emit(
            $this -> websocket -> getSid(),
            [ 'closeWindow', [ 'windowUuid' => $windowUuid ] ]
        );
    }


    /**
     * @param string $sid
     * @return Arr
     */
    public function getWindowUuidMap(string $sid): Arr {
        if (empty($this->windowUuidMap[$sid])) $this->windowUuidMap[$sid] = new Arr();

        return $this->windowUuidMap[$sid];
    }
}
