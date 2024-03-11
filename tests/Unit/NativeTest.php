<?php

namespace iflowTest\Unit;

use iflow\native\Console\Console;
use iflow\native\Native;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class NativeTest extends TestCase {


    public function testRun() {

        // 启动应用
//        (new Native()) -> start();


        $a = [ 'a' => 1 ];
        $b = [ 'a' => 2 ];

        var_dump(
            array_merge(
                $a, $b
            )
        );

//        (new Console()) -> exec([ 'dir' ], function (Process $process) {
//        });

        $this -> assertTrue(true);
    }

}