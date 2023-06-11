<?php

declare(strict_types=1);

namespace core;

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
    private $rerender = false;
    private $eventCount = 0;

    protected function render($componentTemplate)
    {
        $this->componentName = str_replace('src\\components\\', '', get_class($this));
        $this->componentTemplate = $componentTemplate;
        $this->renderTemplate();
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setRerender()
    {
        $this->rerender = true;
    }

    private function renderTemplate()
    {
        $this->processedTemplatePath = BUILT_COMPONENTS . $this->componentTemplate . '.php';
        $this->templatePath = TEMPLATES . DS . $this->componentTemplate . '.php';

        if (file_exists($this->processedTemplatePath) && !DEV) {
            return $this->includeTemplate();
        } else {
            $this->buildTemplate();
            return $this->includeTemplate();
        }
    }

    private function includeTemplate()
    {
        extract($this->data);

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
        file_put_contents($this->processedTemplatePath, $this->contents);
    }

    private function parseTemplate()
    {
        $this->bind();
        $this->on();

        $this->contents = str_replace(
            ['{@', '@}', '{{', '}}'],
            ['$params[\'', '\']', '<?php echo $params[\'', '\']?>', '<>'],
            $this->contents
        );

        $this->contents = '<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>">' . $this->contents;

        if ($this->rerender) {
            $this->contents .= '</div>';
        } else {
            $this->contents .= '<script>' . $this->js . '</script></div>';
        }

        $this->contents = preg_replace("/[\r\n]+/", '', $this->contents);
    }

    private function on()
    {
        preg_match_all('/@on:.*=\w+/', $this->contents, $eventListeners);
        foreach ($eventListeners[0] as $listener) {
            $elementIdentifier = $this->echoIdentifier . '__' . $this->eventCount;
            $this->contents = preg_replace('/@on:.*=\w+/', 'x-identifier="' . $elementIdentifier . '"', $this->contents, 1);
            $listener = explode('=', preg_replace('/@on:/', '', $listener));
            $this->eventCount++;
            if ($this->rerender) {
                continue;
            }

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
            $this->contents = preg_replace('/@bind:=\w+/', 'x-identifier="' . $elementIdentifier . '" value="<?php echo $params[\'' . $property . '\']?>"', $this->contents, 1);
            $this->eventCount++;

            if ($this->rerender) {
                continue;
            }

            $this->js .= str_replace(
                ['&identifier&', '%parent_identifier%', '%component%', '%property%'],
                [$elementIdentifier, $this->echoIdentifier, $this->componentName, $property],
                'bind(\'&identifier&\',\'%parent_identifier%\',\'%component%\',\'%property%\');'
            );
        }
    }
}