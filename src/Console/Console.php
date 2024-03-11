<?php

namespace iflow\native\Console;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class Console {

    protected ConsoleOutput $consoleOutput;

    public function __construct() {
        $this->consoleOutput = new ConsoleOutput();
    }

    public function exec(array $commands, ?callable $callback = null, string $cwd = __DIR__): bool {

        $command = array_shift($commands);
        $callback = $command['callback'] ?? $callback;

        if (isset($command['callback'])) unset($command['callback']);

        if (empty($command)) {
            return $callback && $callback();
        }

        $input = new InputStream();
        $process = new Process($command, $cwd, input: $input, timeout: 0);

        $process -> start(
            function () use ($callback, $process, $input, $commands, $cwd) {
                if ((is_callable($callback) && $callback($process, $input)) !== false) {
                    $this->exec($commands, $callback, $cwd);
                }
            },
        );

        return true;
    }

    /**
     * 向控制台写入信息
     * @param string $message
     * @return void
     */
    public function writeln(string $message): void {
        $this->consoleOutput -> writeln($message);
    }

}