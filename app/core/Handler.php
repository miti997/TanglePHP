<?php

declare(strict_types=1);

namespace app\core;

class Handler
{
    public $data = array();

    public function loadComponent($componentName, $additionalData = [])
    {
        $componentName = str_replace('_', ' ', $componentName);
        $componentName = ucwords($componentName);
        $componentName = str_replace(' ', '', $componentName);
        $componentName = 'app\src\components\\' . $componentName;
        $data = ['identifier' => 'component_' . bin2hex(random_bytes(16))];
        if (!empty($additionalData)) {
            $data['params'] = $additionalData;
        }
        return $this->makeComponent($componentName, $data);
    }

    public function rerender()
    {
        $data = json_decode($_POST['data'], true);
        $data['rerender'] = true;
        $componentName = 'app\src\components\\' . $data['component'];
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

        $component->Handler = $this;

        return $component->mount();
    }
}
