<?php

declare(strict_types=1);

/*
 * Copyright (c) 2023 Gozman-Pop Dumitru
 *
 * Licensed under the MIT License.
 */
namespace app\src\components;

class Component
{
    private $componentTemplate;
    private $processedTemplatePath;
    private $templatePath;
    private $data;
    private $componentName;
    private $contents;
    private $js;
    private $echoIdentifier = '<?php echo $identifier?>';
    private $eventCount = 0;

    public function beforeMount()
    {

    }

    protected function render($componentTemplate)
    {
        $this->componentName = str_replace('app\\src\\components\\', '', get_class($this));
        $this->componentTemplate = $componentTemplate;
        $this->renderTemplate();
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    private function renderTemplate()
    {
        $this->processedTemplatePath = BUILT_COMPONENTS . str_replace('/', DS, $this->componentTemplate) . '.php';
        $this->templatePath = TEMPLATES . str_replace('/', DS, $this->componentTemplate) . '.php';


            return $this->includeTemplate();
    }

    private function includeTemplate()
    {
        extract($this->data['params']);
        extract(['identifier' => $this->data['identifier']]);

        if (!DEV && file_exists($this->processedTemplatePath) && filemtime($this->processedTemplatePath) > filemtime($this->templatePath)) {
            return require $this->processedTemplatePath;
        } else {
            $this->buildTemplate();
            return require $this->processedTemplatePath;
        }
    }

    private function buildTemplate()
    {
        $this->contents = file_get_contents($this->templatePath);
        $this->parseTemplate();
        $directory = dirname($this->processedTemplatePath);
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        file_put_contents($this->processedTemplatePath, $this->contents);
    }

    private function parseTemplate()
    {
        $this->bind();
        $this->on();
        $this->load();
        $this->phpLogic();

        $this->contents = str_replace(
            [
                '{{!',
                '{{',
                '}}',
                '@<@',
                '@>@',
            ],
            [
                '<?php echo ',
                '<?php echo htmlspecialchars(',
                ')?>',
                '<?php',
                '?>'
            ],
            $this->contents
        );

        $this->contents = '<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>">' . PHP_EOL . $this->contents;


        $this->contents .= PHP_EOL . '<script>' . $this->js . '</script>' . PHP_EOL . '</div>';
    }

    private function on()
    {
        preg_match_all('/@on:.*=\w+/', $this->contents, $eventListeners);
        foreach ($eventListeners[0] as $listener) {
            $elementIdentifier = $this->echoIdentifier . '__' . $this->eventCount;
            $this->contents = preg_replace('/@on:.*=\w+/', 'x-identifier="' . $elementIdentifier . '"', $this->contents, 1);
            $listener = explode('=', preg_replace('/@on:/', '', $listener));
            $this->eventCount++;

            $this->js .= str_replace(
                ['%event%', '%identifier%', '%parent_identifier%', '%component%', '%method%'],
                [$listener[0], $elementIdentifier, $this->echoIdentifier, $this->componentName, $listener[1]],
                'on(\'%event%\',\'%identifier%\',\'%parent_identifier%\',\'%component%\',\'%method%\');'
            );
        }
    }

    private function bind()
    {
        preg_match_all('/@bind:=\w+/', $this->contents, $binds);
        foreach ($binds[0] as $property) {
            $property = str_replace('@bind:=', '', $property);
            $elementIdentifier = $this->echoIdentifier . '__' . $this->eventCount;
            $this->contents = preg_replace('/@bind:=\w+/', 'x-identifier="' . $elementIdentifier . '" value="<?php echo $' . $property . '?>"', $this->contents, 1);
            $this->eventCount++;

            $this->js .= str_replace(
                ['&identifier&', '%parent_identifier%', '%component%', '%property%'],
                [$elementIdentifier, $this->echoIdentifier, $this->componentName, $property],
                'bind(\'&identifier&\',\'%parent_identifier%\',\'%component%\',\'%property%\');'
            );
        }
    }

    private function load()
    {
        preg_match_all('/@load:[\$\w\,\ \[\'\=\>\]]+/', $this->contents, $loads);

        foreach ($loads[0] as $load) {
            preg_match('/@load:[\$\w]+/', $load, $componentName);
            $componentName = str_replace('@load:', '', $componentName[0]);
            preg_match('/\[.+\]/', $load, $params);
            if (empty($params)) {
                $params = '';
            } else {
                $params = ',' . $params[0];
            }

            $this->contents = preg_replace('/@load:[\$\w\,\ \[\'\=\>\]]+/', '<?php $this->Handler->load("' . $componentName . '"' . $params . ')?>', $this->contents, 1);
        }
    }

    private function phpLogic()
    {
        preg_match_all('/@each\s.*:|@for\s.*:|@if\s.*:|@elif\s.*:|@else\s:|@end/', $this->contents, $matches);

        foreach ($matches[0] as $match) {
            $endTag = ') { @>@';

            if (preg_match('/@else\s:/', $match)) {
                $endTag = ' { @>@';
            }

            $match = str_replace(
                ['@each ', '@for ', '@if ', '@elif ', '@else ', ' :', '@end'],
                ['@<@ foreach (', '@<@ for (', '@<@ if (', '@<@ } elseif (', '@<@ } else ', $endTag, '@<@ } @>@'],
                $match
            );
            $this->contents = preg_replace('/@each\s.*:|@for\s.*:|@if\s.*:|@elif\s.*:|@else\s:|@end/', $match, $this->contents, 1);
        }

        $this->contents = preg_replace('/@>@(\s*)@<@/', '', $this->contents);
    }
}
