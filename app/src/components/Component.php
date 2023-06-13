<?php

declare(strict_types=1);

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

        if (file_exists($this->processedTemplatePath) && !DEV) {
            return $this->includeTemplate();
        } else {
            $this->buildTemplate();
            return $this->includeTemplate();
        }
    }

    private function includeTemplate()
    {
        extract($this->data['params']);
        extract(['identifier' => $this->data['identifier']]);

        if (filemtime($this->processedTemplatePath) > filemtime($this->templatePath)) {
            return require $this->processedTemplatePath;
        } else {
            return $this->buildTemplate();
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

        $this->contents = str_replace(
            [
                '{{',
                '}}',
                '@:@',//this needs to be here
                '@each ',
                '@if ',
                '@for ',
                ':@'
            ],
            [
                '<?php echo htmlspecialchars(',
                ')?>',
                '<?php }?>',
                '<?php foreach(',
                '<?php if(',
                '<?php for(',
                '){?>',
            ],
            $this->contents
        );

        $this->contents = '<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>">' . PHP_EOL . $this->contents;


        $this->contents .= PHP_EOL . '<script>' . $this->js . '</script></div>';
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
        preg_match_all('/@load:\w+/', $this->contents, $loads);
        foreach ($loads[0] as $load) {
            $load = str_replace('@load:', '<?php $this->Handler->load(\'', $load) . '\')?>';
            $this->contents = preg_replace('/@load:\w+/', $load, $this->contents, 1);
        }
    }
}