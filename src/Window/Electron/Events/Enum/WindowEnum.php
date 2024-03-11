<?php

namespace iflow\native\Window\Electron\Events\Enum;

use iflow\native\Window\Electron\Channel\MessageChannel\MessageChannel;
use iflow\native\Window\Electron\Channel\MessageChannel\WindowEventMessageChannel;
use iflow\native\Window\NetChannel\WebSocket\Formatter\PackageFormatter;
use iflow\native\Window\NetChannel\WebSocket\Websocket;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

enum WindowEnum: string {

    // 初始化窗口
    case INITIALIZATION = 'initialization';

    // 创建窗口
    case OPEN = 'open';

    // 注册窗口
    case REGISTER = 'register';

    // 关闭窗口
    case CLOSE = 'close';

    // 关闭全部窗口
    case CLOSE_ALL = 'close_all';

    // 窗口消息
    case WINDOW_MESSAGE_CHANNEL = 'window_message_channel';

    // 窗口事件消息
    case WINDOW_EVENT_CHANNEL = 'window_event_channel';

    // 加载视图
    case RENDER = 'render';

    public function onPackage(
        Websocket $websocket,
        TcpConnection $connection,
        PackageFormatter $data
    ): void {
        match ($this) {
            WindowEnum::INITIALIZATION => $websocket -> emit(
                $websocket -> getSid(), [
                    'createWindow',
                    $websocket -> getNative() -> getMainWindow()
                ]
            ),
            WindowEnum::OPEN => $websocket -> emit(
                $websocket -> getSid(),
                [ 'createWindow', $data -> data ]
            ),
            WindowEnum::REGISTER => $websocket -> getNative()
                -> getWindowUuidMap($websocket -> getSid()) -> offsetSet($data -> data[1]['windowUuid'], $data -> data),
            WindowEnum::RENDER => $this -> renderTemplate(
                $websocket, $connection, $data
            ),
            WindowEnum::CLOSE => $websocket -> getNative()
                -> getWindowUuidMap($websocket -> getSid()) -> offsetUnset($data -> data[1]['windowUuid']),
            WindowEnum::CLOSE_ALL =>
                $websocket -> getNative() -> getConfig() -> getCloseAllCloseProcessService() && Worker::stopAll(),
            WindowEnum::WINDOW_MESSAGE_CHANNEL => (new MessageChannel($websocket, $data)) -> message(),
            WindowEnum::WINDOW_EVENT_CHANNEL => (new WindowEventMessageChannel($websocket, $connection, $data))
                -> message($data -> data[1]['event'] ?? '')
        };
    }

    /**
     * 视图渲染
     * @param Websocket $websocket
     * @param TcpConnection $connection
     * @param PackageFormatter $data
     * @return void
     * @throws \Throwable
     */
    protected function renderTemplate(
        Websocket $websocket,
        TcpConnection $connection,
        PackageFormatter $data
    ): void {

        $renderTemplateHandle = $websocket -> getNative() -> getConfig()
            -> getRenderTemplateHandle();

        if (class_exists($renderTemplateHandle)) {
            $websocket -> senderWindowMessage([
                'html' => (new $renderTemplateHandle($websocket, $connection)) -> render($data -> data[1]['template'] ?? '', $data -> data) ?: 'no-view',
                'type' => 'render'
            ], $data -> data[1]['windowUuid']);
        }
    }

}
