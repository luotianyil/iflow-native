<?php

namespace iflow\native\Window\Electron\Layout\Menu\Options;

use iflow\native\Window\Service\Options\Options;

class MenuOptions extends Options {

    /**
     * @var array<string, MenuItemOptions>
     */
    protected array $menuItems = [];

    public function setMenuItem(string $id, MenuItemOptions $menuItem): MenuOptions {
        $this->menuItems[$id] = $menuItem;
        return $this;
    }

    public function removeMenuItem(string $id): MenuOptions {
        if ($this->hasMenuItem($id)) unset($this->menuItems[$id]);
        return $this;
    }

    public function hasMenuItem(string $id): bool {
        return array_key_exists($id, $this->menuItems);
    }

    /**
     * @return array
     */
    public function getMenuItems(): array {
        return $this->menuItems;
    }

    /**
     * 菜单列表转数组
     * @return array
     */
    public function getMenuItemsToArray(): array {
        $menuItems = [];
        foreach ($this->menuItems as $menuItem) {
            $menuItems[] = $menuItem -> toArray();
        }
        return $menuItems;
    }

}