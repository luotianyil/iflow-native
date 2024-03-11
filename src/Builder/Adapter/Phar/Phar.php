<?php

namespace iflow\native\Builder\Adapter\Phar;

use Phar as _Phar;

class Phar {

    protected _Phar $phar;

    public function __construct(protected array $pharBuilderConfig) {
        $this -> checkOldPhar();
        $this -> replaceRunType();
        $this->phar = new _Phar($this->pharBuilderConfig['phar-path']);
    }

    public function builder(): string|bool {

        try {
            $this->phar -> buildFromDirectory($this->pharBuilderConfig['root']);
            $this->phar -> setDefaultStub($this->pharBuilderConfig['entry'], $this->pharBuilderConfig['web-entry']);

            $this->phar -> compressFiles(_Phar::GZ);

            if ($this->pharBuilderConfig['privatekey'] && file_exists($this->pharBuilderConfig['privatekey'])) {
                $private = openssl_get_privatekey(file_get_contents('private.pem'));
                $pkey = '';
                openssl_pkey_export($private, $pkey);
                $this->phar -> setSignatureAlgorithm(_Phar::OPENSSL, $pkey);
            }

            $this->phar -> stopBuffering();

            $this -> mergeMicroPhar();
            return true;
        } catch (\Throwable $throwable) {
            return $throwable -> getMessage();
        }
    }

    protected function mergeMicroPhar(): bool {
        $micro = str_replace('/', DIRECTORY_SEPARATOR, $this->pharBuilderConfig['micro-path']);
        $phar = str_replace('/', DIRECTORY_SEPARATOR, $this->pharBuilderConfig['phar-path']);
        $outDir = str_replace('/', DIRECTORY_SEPARATOR, $this->pharBuilderConfig['out-path']);

        if (!is_dir($outDir)) mkdir($outDir);

        $outDir .=  DIRECTORY_SEPARATOR;
        if (str_starts_with(PHP_OS, 'WIN')) {
            shell_exec("COPY /b $micro + $phar {$outDir}native.exe");
            @unlink($phar);
            return true;
        }

        if (str_starts_with(PHP_OS, 'DARWIN')){
            shell_exec("xattr -d com.apple.quarantine $micro");
        }

        shell_exec("cat $micro $phar > {$outDir}native");
        shell_exec("chmod 0755 {$outDir}native");
        @unlink($phar);
        return true;
    }

    public function checkOldPhar(): void {
        if (file_exists($this->pharBuilderConfig['phar-path'])) @unlink($this->pharBuilderConfig['phar-path']);
    }

    public function replaceRunType(): bool {

        $nativePath = __DIR__ . '/../../../Native.php';

        $native = file_get_contents($nativePath);

        $native = str_replace(
            "protected string \$RUN_ING_TYPE = 'development'",
            "protected string \$RUN_ING_TYPE = 'production'",
            $native
        );
        return file_put_contents($nativePath, $native);
    }
}
