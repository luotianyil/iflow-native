<?php

namespace iflow\native\Window\Service\Options;

class Options {

    public int|string $windowId = 0;

    public string $title = '';

    public string $icon;

    public string $tipTitle = '';

    public bool $awaitResponse;

    public array $options = [];

    public array $hideFields = [];

    public function __construct(array $options = []) {
        foreach ($options as $optionKey => $optionValue) {
            $this -> setOption($optionKey, $optionValue);
        }
    }

    /**
     * 设置配置
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setOption(string $key, mixed $value): Options {
        if (property_exists($this, $key)) $this -> {$key} = $value;
        return $this;
    }


    protected function toArrayFormatter(array $options = []): array {
        return $options;
    }

    public function toArray(array $options = []): array {
        $value = get_object_vars($this);

        $value = $this->toArrayFormatter($value);

        foreach ($value as $key => $item) {
            if (empty($item)) unset($value[$key]);
        }

        $rows = $this->toArrayFormatter(array_merge($value, $options));
        foreach ($this->hideFields as $field) if (isset($rows[$field])) unset($rows[$field]);

        return $rows;
    }
}