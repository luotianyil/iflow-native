<?php

namespace iflow\native\Window\RenderTemplate;

use iflow\native\Window\Interfaces\RenderTemplateHandleInterface;
use iflow\native\Window\NetChannel\WebSocket\Websocket;
use iflow\template\exception\TemplateViewNotFound;
use iflow\template\Template as ITemplate;

class Template implements RenderTemplateHandleInterface {

    public function __construct(protected Websocket $websocket) {
    }

    /**
     * 视图渲染
     * @param string $template
     * @param array $data
     * @return string
     * @throws TemplateViewNotFound|\Exception
     */
    public function render(string $template, array $data): string {
        // TODO: Implement render() method.

        if (!$template) return '';

        $templateRender = new ITemplate($this -> websocket -> getNative() -> getConfig() -> getDefaultTemplateConfig());
        $templateRender -> assign('event', $data[0]) -> assign('data', $data[1]);

        try {
            return $templateRender -> fetch($template);
        } catch (TemplateViewNotFound) {
            return $templateRender -> display($template, $data);
        }
    }
}