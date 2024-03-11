<?php

namespace iflowTest\Unit;

use iflow\native\FFI\FFILoader;
use PHPUnit\Framework\TestCase;

class FFITest extends TestCase {

    const messageType = 64;


    public function testFFI() {

        $FFILoader = FFILoader::getInstance();

        $path = __DIR__ . '/../../ffi/';

        $window = $FFILoader -> FFIDEFLoader($path . 'main.h', $path . 'libC_PHPLibrary.dll');

//        $window -> hello();
//        $window -> message("message Tips", "tip", self::messageType);

        $window -> registerWindow(640, 480, "my window", 1);

        self::assertTrue(true);
    }

}