<?php

declare(strict_types=1);

namespace core;

class Handler
{
    public $data = array();

    public function start()
    {
        $uri = $_SERVER['REQUEST_URI'];

        $uri = explode('/', $uri);

        if ($uri[1] == 'component_rerender') {
            return $this->rerender($uri);
        }

        if (empty($uri[1])) {
            $page = 'home';
        } else {
            $page = $uri[1];
        }

        foreach ($uri as $key => $param) {
            if ($key < 2) {
                continue;
            }
            $this->data[] = $param;
        }

        echo '<script src="/resources/main.js"></script>';
        return include PAGES . $page . '.php';
    }

    public function loadComponent($componentName, $additionalData = [])
    {
        $componentName = str_replace('_', ' ', $componentName);
        $componentName = ucwords($componentName);
        $componentName = str_replace(' ', '', $componentName);
        $componentName = 'src\components\\' . $componentName;
        $data = ['identifier' => 'component_' . bin2hex(random_bytes(16))];
        if (!empty($additionalData)) {
            $data['params'] = $additionalData;
        }
        return $this->makeComponent($componentName, $data);
    }

    private function rerender()
    {
        $data = json_decode($_POST['data'], true);
        $data['rerender'] = true;
        $componentName = 'src\components\\' . $data['component'];
        return $this->makeComponent($componentName, $data);
    }

    private function makeComponent($componentName, $data = [])
    {
        $component = new $componentName();

        if (!empty($data['params'])) {
            foreach ($data['params'] as $property => $value) {
                $component->{$property} = $value;
            }
        }

        if (isset($data['method'])) {
            $component->{$data['method']}();
        }

        if (isset($data['rerender']) && $data['rerender']) {
            $component->setRerender();
        }
        $data['params'] = get_object_vars($component);
        $component->setData($data);

        return $component->mount();
    }
}
