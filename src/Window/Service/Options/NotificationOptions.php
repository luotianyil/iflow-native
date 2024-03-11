<?php

namespace iflow\native\Window\Service\Options;

class NotificationOptions extends Options {

    public string $subtitle;

    public string $body;

    public bool $silent;

    public bool $hasReply;

    public string $timeoutType = 'default';

    public string $replyPlaceholder;

    public string $sound;

    public string $urgency;

    public array $actions = [];

    public string $closeButtonText;

    public string $toastXml;

    /**
     * Notification 回调事件
     * @description show => notification_show_event, click, reply, close, action, failed
     * @var string[]
     */
    public array $events = [];

}