<?php

declare(strict_types=1);

/*
 * Copyright (c) 2023 Gozman-Pop Dumitru
 *
 * Licensed under the MIT License.
 */
namespace app\src\components;

use app\src\components\Component;

class NameInput extends Component
{
    public $name = '';
    public $names = ['Jhon', 'Andrew', 'Karoline'];

    public function mount()
    {
        return $this->render('name_input');
    }

    public function addName()
    {
        $this->names[] = $this->name;
    }
}
