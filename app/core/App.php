<?php

declare(strict_types=1);

namespace app\core;

use app\core\Handler;

class App
{
    public function start()
    {
        $handler = new Handler();
        $uri = $_SERVER['REQUEST_URI'];

        if ($uri == '/') {
            $component = 'home';
        } else {
            $component = str_replace('/', DS, ltrim($uri, '/'));
        }

        $uri = explode('/', $uri);

        if ($uri[1] == 'component_rerender') {
            return $handler->rerender($uri);
        }

        echo '<script src="/app/resources/main.js"></script>';

        return $handler->loadComponent($component);
    }
}
