<?php

namespace iflow\native\Window\Electron\Layout\Menu\Options;

use iflow\Helper\Str\Str;
use iflow\native\Window\Service\Options\Options;

class MenuItemOptions extends Options {

    public int $id;

    public string $event;

    public string $role;

    public string $type;

    public string $label;

    public string $sublabel;

    public string $toolTip;

    public string $accelerator;

    public bool $enabled;

    public bool $acceleratorWorksWhenHidden;

    public bool $visible;

    public bool $checked;

    public bool $registerAccelerator;

    public array $sharingItem;

    public MenuOptions $submenu;

    /**
     * @var string[]
     */
    public array $before;

    /**
     * @var string[]
     */
    public array $after;

    /**
     * @var string[]
     */
    public array $beforeGroupContaining;

    /**
     * @var string[]
     */
    public array $afterGroupContaining;

    protected function toArrayFormatter(array $options = []): array {
        if (array_key_exists('submenu', $options) && is_object($options['submenu'])) {
            $options['submenu'] = $options['submenu'] -> getMenuItemsToArray();
        }

        if (empty($options['id'])) $options['id'] = Str::genUuid();

        return $options;
    }
}
