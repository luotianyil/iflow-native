<?php

namespace iflow\native\FFI;

use FFI;
use Psr\Container\ContainerInterface;

class FFILoader implements ContainerInterface {

    // 当前容器
    protected static ?FFILoader $instance = null;

    private static ?FFI $ffi = null;

    public static function getInstance(string $name = ''): static {
        if (is_null(static::$instance)) static::$instance = new static();
        if (static::$instance instanceof \Closure) return (new static())();
        if ($name) self::$ffi = FFI::scope($name);
        return self::$instance;
    }

    /**
     * @var array<string, FFI>
     */
    protected array $ffiUseObject;

    public function register(string $ffiH, FFI $ffi): FFILoader {
        $this->ffiUseObject[$ffiH] = $ffi;
        return $this;
    }

    /**
     * @param string $ffiH
     * @param bool $newCreate
     * @return FFI
     */
    public function create(string $ffiH, bool $newCreate = false): FFI {

        if (!$newCreate && isset($this->ffiUseObject[$ffiH])) {
            return $this->ffiUseObject[$ffiH];
        }

        return $this->ffiUseObject[$ffiH] = (self::$ffi ?: 'FFI')::load($ffiH);
    }

    public function has(string $id): bool {
        return isset($this->ffiUseObject[$id]);
    }

    public function get(string $id): FFI|null {
        // TODO: Implement get() method.
        return $this->has($id) ? $this->ffiUseObject[$id] : null;
    }

    public function delete(string $ffiH): bool {
        if ($this->has($ffiH)) unset($this->ffiUseObject[$ffiH]);
        return true;
    }

    /**
     * @param string $cCode
     * @param string|null $library
     * @return FFI
     */
    public function FFIDEFLoader(string $cCode, ?string $library = null): FFI {

        if (file_exists($cCode)) $cCode = file_get_contents($cCode);

        return (self::$ffi ?: 'FFI')::cdef($cCode, $library);
    }

}
