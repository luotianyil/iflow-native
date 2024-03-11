<?php

namespace iflow\native\Window\Interfaces;

interface RenderTemplateHandleInterface {

    /**
     * 渲染模板
     * @param string $template 模板内容/模板文件
     * @param array $data 模板参数
     * @return string
     */
    public function render(string $template, array $data): string;

}