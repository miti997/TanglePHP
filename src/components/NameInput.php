<?php

declare(strict_types=1);

namespace src\components;

use core\Component;

class NameInput extends Component
{
    public $name = '';
    public $names = [];

    public function mount()
    {
        return $this->render('name_input');
    }

    public function addName()
    {
        $this->names[] = $this->name;
    }
}
