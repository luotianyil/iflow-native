<?php

namespace iflowTest\Unit;

use iflow\native\Window\Service\Dialog;
use iflow\native\Window\Service\Options\DialogOptions;
use PHPUnit\Framework\TestCase;

class DialogTest extends TestCase {


    public function testDialog() {

        $dialog = new DialogOptions();
        $dialog -> setOption('icon', '2333');

        var_dump($dialog -> toArray([ 'icon' => '123123' ]));

        self::assertTrue(true);
    }

}