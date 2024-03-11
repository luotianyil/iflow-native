<?php

namespace iflow\native\Builder;

use iflow\native\Builder\Adapter\Phar\Phar;
use iflow\native\Config;
use iflow\native\Console\Console;

class Builder {

    protected Console $console;

    public function builder(Config $config, Console $console): bool {
        $this->console = $console;
        if (!$this->builderPhar($config -> getBuilder('phar'))) {
            return false;
        }

        $this->console -> writeln('builder success...');
        return $this->builderElectron($config -> toArray());
    }

    public function builderPhar(array $pharConfig): bool {
        if (($builderResult = (new Phar($pharConfig)) -> builder()) !== true) {
            $this->console -> writeln($builderResult);
            return false;
        }
        return true;
    }

    public function builderElectron(array $electronConfig): bool {

        if (!is_dir($electronConfig['electron_path'])) return true;

        $nativeBuildOutPath = str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            $electronConfig['builder']['phar']
        ).DIRECTORY_SEPARATOR;

        $app = file_exists($nativeBuildOutPath.'/native')
            ? $nativeBuildOutPath.'native'
            : $nativeBuildOutPath.'native.exe';

        rename($app, $electronConfig['electron_path']);
        return true;
    }
}
