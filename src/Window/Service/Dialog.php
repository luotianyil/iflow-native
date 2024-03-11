<?php

namespace iflow\native\Window\Service;

use iflow\native\Native;
use iflow\native\Window\Interfaces\RegisterServiceInterface;
use iflow\native\Window\Service\Options\DialogOptions;

class Dialog implements RegisterServiceInterface {

    protected Native $native;

    public function boot(Native $native): Dialog {
        // TODO: Implement boot() method.
        $this->native = $native;
        return $this;
    }

    public function dialog(DialogOptions $dialogOptions, array $options = []): bool {
        return $this->native -> getWebsocket() -> emitWindowMessageChannel(
            $this->native->getWebsocket()->getSid(),
            [ 'event' => 'dialog', 'options' => $dialogOptions -> toArray(), ...$options ]
        );
    }
}
