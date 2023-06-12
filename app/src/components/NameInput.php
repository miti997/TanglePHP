<?php

declare(strict_types=1);

namespace app\src\components;

use app\core\Component;

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
