<?php

namespace iflowTest\Unit;

use iflow\native\Window\Electron\Layout\Menu\Options\MenuItemOptions;
use iflow\native\Window\Electron\Layout\Menu\Options\MenuOptions;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase {


    public function testMenu() {
        $menu = new MenuOptions();

        $menu -> setMenuItem('_menu', new MenuItemOptions([
            'label' => '测试菜单',
            'click' => 'test_menu_event',
           'submenu' => (new MenuOptions()) -> setMenuItem('_submenu', new MenuItemOptions([
                'label' => '测试子菜单',
                'click' => 'test_submenu_event'
            ]))
        ]));

//        var_dump($menu);
        var_dump($menu -> getMenuItemsToArray());
        self::assertTrue(true);
    }

}