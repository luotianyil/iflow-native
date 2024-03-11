<?php

namespace iflowTest\Unit;

use iflow\native\Window\Electron\Layout\Menu\Options\MenuItemOptions;
use iflow\native\Window\Electron\Layout\Menu\Options\TrayOptions;
use PHPUnit\Framework\TestCase;

class TrayTest extends TestCase {


    public function testTray() {
        $menu = new TrayOptions();

        $menu
            -> setOption('icon', '2333')
            -> setOption('title', '2333')
            -> setOption('tipTitle', '2333')
            -> setMenuItem('_menu', new MenuItemOptions([
                'label' => '测试菜单',
                'click' => 'test_menu_event'
            ]));

//        var_dump($menu);
        var_dump($menu -> toArray());
        self::assertTrue(true);
    }

}