<?php

declare(strict_types=1);

/*
 * Copyright (c) 2023 Gozman-Pop Dumitru
 *
 * Licensed under the MIT License.
 */
namespace app\src\components;

use app\src\components\Component;

class Home extends Component
{
    public function mount()
    {
        return $this->render('home');
    }
}
